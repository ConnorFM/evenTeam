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
                                            (`name`, `capacity`, `image`, `description`)
                                            VALUES (:name, :capacity, :image)");
        $statement->bindValue('name', $room['name'], \PDO::PARAM_STR);
        $statement->bindValue('capacity', $room['capacity'], \PDO::PARAM_INT);
        $statement->bindValue('image', $room['image'], \PDO::PARAM_STR);
        $statement->bindValue('description', $room['description'], \PDO::PARAM_STR);
/**
        if ($statement->execute()) {
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

        // prepared request
        $statement = $this->pdo->prepare("UPDATE $this->table SET `title` = :title WHERE id=:id");
        $statement->bindValue('name', $room['name'], \PDO::PARAM_STR);
        $statement->bindValue('capacity', $room['capacity'], \PDO::PARAM_INT);
        $statement->bindValue('image', $room['image'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
