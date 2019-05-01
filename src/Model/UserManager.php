<?php

namespace App\Model;

class UserManager extends AbstractManager
{
    const TABLE = "users";

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    // Create a user in the Database
    public function insert($user)
    {
        $insert = $this->pdo->prepare("INSERT INTO $this->table
                                     (`firstname`, `lastname`, `email`, `status_id`, `image`, `password`)
                                     VALUES (:firstname, :lastname, :email, :status_id, :image, :password)");
        $insert->bindvalue('firstname', $user['firstname'], \PDO::PARAM_STR);
        $insert->bindvalue('lastname', $user['lastname'], \PDO::PARAM_STR);
        $insert->bindvalue('email', $user['email'], \PDO::PARAM_STR);
        $insert->bindvalue('status_id', $user['status_id'], \PDO::PARAM_INT);
        $insert->bindvalue('image', $user['image'], \PDO::PARAM_STR);
        $insert->bindvalue('password', $user['password'], \PDO::PARAM_STR);

        $insert->execute();
    }

    public function selectFirstname(): array
    {
        return $this->pdo->query('SELECT firstname FROM ' . $this->table)->fetchAll();
    }


    // Delete a user in the database
    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM user_event
                                        WHERE user_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        // prepared request
        $delete = $this->pdo->prepare("DELETE FROM $this->table
                                        WHERE id=:id");
        $delete->bindValue('id', $id, \PDO::PARAM_INT);
        $delete->execute();
    }

    // Uptade a user
    public function update($user)
    {
        $update = $this->pdo->prepare("UPDATE $this->table
                                        SET `firstname` = :firstname,
                                            `lastname` = :lastname,
                                            `email` = :email,
                                            `status_id` = :status_id,
                                            `image` = :image,
                                            `password` = :password
                                        WHERE id=:id");
        $update->bindvalue('id', $user['id'], \PDO::PARAM_INT);
        $update->bindvalue('firstname', $user['firstname'], \PDO::PARAM_STR);
        $update->bindvalue('lastname', $user['lastname'], \PDO::PARAM_STR);
        $update->bindvalue('email', $user['email'], \PDO::PARAM_STR);
        $update->bindvalue('status_id', $user['status_id'], \PDO::PARAM_INT);
        $update->bindvalue('image', $user['image'], \PDO::PARAM_STR);
        $update->bindvalue('password', $user['password'], \PDO::PARAM_STR);

        return $update->execute();
    }

    // Return email and password
    public function getLog($email)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE email=:email");
        $statement->bindvalue('email', $email, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }

    // Return status and id of the user
    public function getSession($email)
    {
        $statement = $this->pdo->prepare("SELECT id, status_id, lastname FROM $this->table WHERE email=:email");
        $statement->bindvalue('email', $email, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }

    // Return the user Email
    public function getOneByEmail($email)
    {
        $statement = $this->pdo->prepare("SELECT email FROM $this->table WHERE email=:email");
        $statement->bindvalue('email', $email, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch(); //array
    }

    public function getAllUsersEvents()
    {
        $statement = $this->pdo->prepare("SELECT user_id, event_id, firstname, lastname 
                                                    FROM user_event 
                                                    JOIN users ON users.id = user_id");
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getEmails($user_id)
    {
        $statement = $this->pdo->prepare("SELECT email
                                          FROM users
                                          WHERE id= :id");
        $statement->bindvalue('id', $user_id, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch(); //array
    }
}
