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
     * @param array $event Representation of the Object 'event' as an array can be manipulated.
     */
    public function insert(array $event)
    {
        // Prepared request
        $statement = $this->pdo->prepare("
            INSERT INTO $this->table (name,creator, date_start, date_end, room_id, description)
            VALUES (:name, :creator, :date_start, :date_end, :room_id, :description)
            ");
        $statement->bindValue('name', $event['name'], \PDO::PARAM_STR);
        $statement->bindValue('creator', $event['creator'], \PDO::PARAM_STR);
        $statement->bindValue('date_start', $event['date_start'], \PDO::PARAM_STR);
        $statement->bindValue('date_end', $event['date_end'], \PDO::PARAM_STR);
        $statement->bindValue('room_id', $event['room_id'], \PDO::PARAM_INT);
        $statement->bindValue('description', $event['description'], \PDO::PARAM_STR);

        if ($statement->execute()) {
            $event_id = $this->pdo->lastInsertId();

            foreach ($event['user_id'] as $value) {
                $statement = $this->pdo->prepare("
                    INSERT INTO user_event (event_id, user_id)
                    VALUES (:event_id, :userId)
                ");

                $statement->bindValue('event_id', $event_id, \PDO::PARAM_INT);
                $statement->bindValue('userId', $value, \PDO::PARAM_INT);
                $statement->execute();
            }
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
     * @param array $event Representation of the Object 'event' as an array can be manipulated.
     * @return bool
     */
    public function update(array $event)
    {
        // prepared request

        $statement = $this->pdo->prepare("DELETE FROM user_event where event_id=:id");
        $statement->bindValue('id', $event['id'], \PDO::PARAM_INT);
        $statement->execute();


        $statement = $this->pdo->prepare("UPDATE $this->table
                                             SET `name`=:name,
                                                 `date_start` = :date_start,
                                                 `date_end` = :date_end,
                                                 `room_id` = :room_id,
                                                 `description` = :description
                                             WHERE id=:id");
         $statement->bindValue('id', $event['id'], \PDO::PARAM_INT);
         $statement->bindValue('name', $event['name'], \PDO::PARAM_STR);
         $statement->bindValue('date_start', $event['date_start'], \PDO::PARAM_STR);
         $statement->bindValue('date_end', $event['date_end'], \PDO::PARAM_STR);
         $statement->bindValue('room_id', $event['room_id'], \PDO::PARAM_INT);
         $statement->bindValue('description', $event['description'], \PDO::PARAM_STR);

        foreach ($event['user_id'] as $value) {
            $statement = $this->pdo->prepare("
                    INSERT INTO user_event (event_id, user_id)
                    VALUES (:event_id, :userId)

                ");

            $statement->bindValue('event_id', $event['id'], \PDO::PARAM_INT);
            $statement->bindValue('userId', $value, \PDO::PARAM_INT);
            $statement->execute();
        }
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
     * This method will execute the SQL request which will select
     * all events associate with one user from database via PDO.
     *
     * @param int $user_id
     * @return array of events of a user
     */

    public function getUserEvents($user_id)
    {
        // Prepared request
        $statement = $this->pdo->prepare("SELECT id,creator, name, date_start, date_end, room_id, description
                                          FROM user_event
                                          JOIN events ON id = user_event.event_id
                                          WHERE user_id = :user_id 
                                          ORDER BY date_start;");
        $statement->bindValue('user_id', $user_id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
    // return every events and user(s) in the event
    public function getEventUsers()
    {
        $statement = $this->pdo->prepare("SELECT * from user_event;");
        $statement->execute();
        return $statement->fetchAll();
    }

        /**
     * Select every 'event' objects for one user included event(s) created by the user.
     *
     * This method will execute the SQL request which will select
     * all events associate with one user from database via PDO.
     *
     * @param int $user_id
     * @return array of events of a user
     */

    public function getUserEventsAndCreator($user_id)
    {
        // Prepared request
        $statement = $this->pdo->prepare("SELECT id,creator, name, date_start, date_end, room_id, description
                                          FROM user_event
                                          JOIN events ON id = user_event.event_id
                                          WHERE user_id = :user_id 
                                          OR creator= :user_id
                                          ORDER BY date_start;");
        $statement->bindValue('user_id', $user_id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
    // return every even
}
