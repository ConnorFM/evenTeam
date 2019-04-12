<?php


namespace App\Model;

class EventManager extends AbstractManager
{

    const TABLE = 'events';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param array $event from the create event form
     * @return int
     */
    public function insert(array $event): int
    {

        /* A IMPLEMENTER DANS LE CONTROLLER DU ADD FORM ET ADAPTER DANS CE MANAGER
         $date = DateTime::createFromFormat('d/m/Y', $_POST['date']);
         $query = $db->prepare('INSERT INTO table(date) VALUES(:date)'); // $db étant une instance de PDO
         $query->bindValue(':date', $date->format('Y-m-d'), PDO::PARAM_STR);
         $query->execute();
        */

        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table (name, date_start, date_end, room_id, description ) 
                                                    VALUES (:title, :date_start, :date_end, :room_id, :description)");
        $statement->bindValue('name', $event['name'], \PDO::PARAM_STR);
        $statement->bindValue('date_start', $event['date_start'], \PDO::PARAM_STR);
        $statement->bindValue('date_end', $event['date_end'], \PDO::PARAM_STR);
        $statement->bindValue('room_id', $event['room_id'], \PDO::PARAM_INT);
        $statement->bindValue('description', $event['description'], \PDO::PARAM_STR);

        $statement->execute();
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
     * @param array $item
     * @return bool
     */
    public function update(array $item):bool
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE $this->table SET `title` = :title WHERE id=:id");
        $statement->bindValue('id', $item['id'], \PDO::PARAM_INT);
        $statement->bindValue('title', $item['title'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
