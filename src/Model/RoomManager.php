<?php


namespace App\Model;

class RoomManager extends AbstractManager
{
    const TABLE = 'room';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param array $room
     * @return int
     */
    public function insert($room)//: int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table
                                            (`name`, `capacity`, `description`)
                                            VALUES (:name, :capacity, :description)");
        $statement->bindValue('name', $room['name'], \PDO::PARAM_STR);
        $statement->bindValue('capacity', $room['capacity'], \PDO::PARAM_INT);
        $statement->bindValue('description', $room['description'], \PDO::PARAM_STR);
/**
        if ($statement->exmessagesecute()) {
            return (int)$this->pdo->lastInsertId();
        }
**/     $statement->execute();
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
        $updateStatement = $this->pdo->prepare("UPDATE events SET room_id = NULL WHERE room_id = :id");
        $updateStatement->bindValue('id', $id, \PDO::PARAM_INT);
        $updateStatement->execute();

        $statement = $this->pdo->prepare("DELETE FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @param array $room
     * @return bool
     */
    public function update(array $room):bool
    {
        $statement = $this->pdo->prepare("UPDATE $this->table
                                         SET `name` = :name,
                                            `capacity` = :capacity,
                                            `description` = :description,
                                         WHERE id=:id");
        $statement->bindvalue('id', $room['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $room['name'], \PDO::PARAM_STR);
        $statement->bindValue('capacity', $room['capacity'], \PDO::PARAM_INT);
        $statement->bindValue('description', $room['description'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
