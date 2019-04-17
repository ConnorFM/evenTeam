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
        $insert = $this->pdo->prepare("INSERT INTO $this->table (`firstname`, `lastname`, `email`, `status_ID`, `image`, `password`) VALUES (:firstname, :lastname, :email, :status_ID, :image, :password)");
        $insert->bindvalue('firstname', $user['firstname'], \PDO::PARAM_STR);
        $insert->bindvalue('lastname', $user['lastname'], \PDO::PARAM_STR);
        $insert->bindvalue('email', $user['email'], \PDO::PARAM_STR);
        $insert->bindvalue('status_ID', $user['status_ID'], \PDO::PARAM_INT);
        $insert->bindvalue('image', $user['image'], \PDO::PARAM_STR);
        $insert->bindvalue('password', $user['password'], \PDO::PARAM_STR);

        $insert->execute();
    }

    // Delete a user in the database
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM user_event WHERE user_id=:id; DELETE FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    // Uptade a user
    public function update($user)
    {
        $update = $this->pdo->prepare("UPDATE $this->table SET `firstname` = :firstname, `lastname` = :lastname, `email` = :email, `status_ID` = :status_ID, `image` = :image, `password` = :password WHERE ID=:id");
        $update->bindvalue('id', $user['ID'], \PDO::PARAM_INT);
        $update->bindvalue('firstname', $user['firstname'], \PDO::PARAM_STR);
        $update->bindvalue('lastname', $user['lastname'], \PDO::PARAM_STR);
        $update->bindvalue('email', $user['email'], \PDO::PARAM_STR);
        $update->bindvalue('status_ID', $user['status_ID'], \PDO::PARAM_INT);
        $update->bindvalue('image', $user['image'], \PDO::PARAM_STR);
        $update->bindvalue('password', $user['password'], \PDO::PARAM_STR);

        return $update->execute();
    }

    // Return email and password
    public function getLog($email)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT ID, email, password, status_ID FROM $this->table WHERE email=:email");
        $statement->bindvalue('email', $email, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }

    // Return status and ID of the user
    public function getSession($email)
    {
        $statement = $this->pdo->prepare("SELECT ID, status_ID FROM $this->table WHERE email=:email");
        $statement->bindvalue('email', $email, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }

    // Return the user Email
    public function getEmail($email)
    {
        $statement = $this->pdo->prepare("SELECT email FROM $this->table WHERE email=:email");
        $statement->bindvalue('email', $email, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch(); //array
    }
}
