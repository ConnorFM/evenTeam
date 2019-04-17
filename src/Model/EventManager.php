<?php
namespace App\Model;

/**
* Manage interaction with user table in the database
*/
class EventManager extends AbstractManager
{
    /**
     *  Initializes this constant.
     */
    const TABLE = 'events';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * Insert into the 'event' Table all the input values.
     *
     * This method will execute the SQL request which will insert inputs into database via PDO.
     *
     * @param array $event Representation of the Object 'event' as an array can be manipulated.
     */
    public function insert(array $event)
    {
        // prepared request
        $statement = $this->pdo->prepare("
            INSERT INTO $this->table (name, date_start, date_end, room_id, description) 
            VALUES (:name, :date_start, :date_end, :room_id, :description)
            ");
        $statement->bindValue('name', $event['name'], \PDO::PARAM_STR);
        $statement->bindValue('date_start', $event['date_start'], \PDO::PARAM_STR);
        $statement->bindValue('date_end', $event['date_end'], \PDO::PARAM_STR);
        $statement->bindValue('room_id', $event['room_id'], \PDO::PARAM_INT);
        $statement->bindValue('description', $event['description'], \PDO::PARAM_STR);

        $statement->execute();
    }

    /**
     * Delete an 'event' object from the 'event' Table by his id.
     *
     * This method will execute the SQL request which will delete an 'event' object from database via PDO.
     *
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
     * Delete an 'event' object from the 'event' Table by his id.
     *
     * This method will execute the SQL request which will delete an 'event' object from database via PDO.
     *
     * @param array $event Representation of the Object 'event' as an array can be manipulated.
     */
    public function update(array $event):bool
    {
        // prepared request
        $statement = $this->pdo->prepare("UPDATE $this->table SET `title` = :title WHERE id=:id");
        $statement->bindValue('id', $event['id'], \PDO::PARAM_INT);
        $statement->bindValue('title', $event['title'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
