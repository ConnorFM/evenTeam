<?php
namespace App\Model;

/**
* Manage interaction with user table in the database
*/
class EventManager extends AbstractManager
{
    /**
     *  Initializes this constant table.
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
     * @param array $events Representation of the Object 'event' as an array can be manipulated.
     */
    public function insert(array $events)
    {
        // Prepared request
        $statement = $this->pdo->prepare("
            INSERT INTO $this->table (name, date_start, date_end, room_id, description) 
            VALUES (:name, :date_start, :date_end, :room_id, :description)
            ");
        $statement->bindValue('name', $events['name'], \PDO::PARAM_STR);
        $statement->bindValue('date_start', $events['date_start'], \PDO::PARAM_STR);
        $statement->bindValue('date_end', $events['date_end'], \PDO::PARAM_STR);
        $statement->bindValue('room_id', $events['room_id'], \PDO::PARAM_INT);
        $statement->bindValue('description', $events['description'], \PDO::PARAM_STR);

        //
        if ($statement->execute()) {
            $event_id = $this->pdo->lastInsertId();
            $statement = $this->pdo->prepare("
            INSERT INTO user_event (event_id, user_id)
            VALUES (:event_id, :userId)
            ");

            $statement->bindValue('event_id', $event_id, \PDO::PARAM_INT);
            $statement->bindValue('userId', $events['user_id'], \PDO::PARAM_INT);
            $statement->execute();
        }
    }

    /**
     * Delete an 'event' object from the 'events' Table by his id.
     *
     * This method will execute the SQL request which will delete an 'event' object from database via PDO.
     *
     * @param int $id
     */
    public function delete(int $id):void
    {
        // prepared request
        
        
        $statement = $this->pdo->prepare("DELETE FROM user_event WHERE event_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        
        $statement->execute();

        $statement = $this->pdo->prepare("DELETE FROM events WHERE id not IN (SELECT event_id FROM user_event)");
        $statement->execute();
    }

    /**
     * Delete an 'event' object from the 'events' Table by his id.
     *
     * This method will execute the SQL request which will delete an 'event' object from database via PDO.
     *
     * @param array $events Representation of the Object 'event' as an array can be manipulated.
     */
    public function update(array $events):bool
    {
        // prepared request
        $statement = $this->pdo->prepare("UPDATE $this->table 
                                            SET `name`=:name,
                                                `date_start` = :date_start,
                                                `date_end` = :date_end,
                                                `room_id` = :room_id,
                                                `description` = :description
                                            WHERE id=:id");
        $statement->bindValue('id', $events['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $events['name'], \PDO::PARAM_STR);
        $statement->bindValue('date_start', $events['date_start'], \PDO::PARAM_STR);
        $statement->bindValue('date_end', $events['date_end'], \PDO::PARAM_STR);
        $statement->bindValue('room_id', $events['room_id'], \PDO::PARAM_INT);
        $statement->bindValue('description', $events['description'], \PDO::PARAM_STR);
        return $statement->execute();
    }

    /**
     * Select every 'event' objects for one room.
     *
     * This method will execute the SQL request which will select
     * all events associate with one room from database via PDO.
     *
     * @param int $room_id
     * @return array of events of a room
     */

    public function getRoomsEvents($room_id)
    {
        // Prepared request
        $statement = $this->pdo->prepare("  SELECT * FROM $this->table
                                            WHERE room_id=:room_id
                                            ORDER BY date_start");
        $statement->bindValue('room_id', $room_id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * Select every 'event' objects for one user.
     *
     *This method will execute the SQL request which will select
     * all events associate with one user from database via PDO.
     *
     * @param int $user_id
     * @return array of events of a user
     */

    public function getUserEvents($user_id)
    {
        // Prepared request
        $statement = $this->pdo->prepare("  SELECT id, name, date_start, date_end, room_id, description  
                                                      FROM user_event
                                                      JOIN events ON id = user_event.event_id
                                                      WHERE user_id= :user_id
                                                      ORDER BY date_start;");
        $statement->bindValue('user_id', $user_id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}
