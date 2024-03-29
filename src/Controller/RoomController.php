<?php


namespace App\Controller;

use App\Model\RoomManager;

class RoomController extends CalendarController
{
    protected $roomManager;

    public function __construct()
    {
        parent::__construct();
        $this->roomManager = new RoomManager();
    }


    public function index()
    {
        $rooms = $this->roomManager->selectAll();
        return $rooms;
    }

    public function show(int $id)
    {
        $room = $this->roomManager->selectOneById($id);
        return $room;
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $room = [
            'name' => $_POST['name'],
            'capacity' => $_POST['capacity'],
            'description' => $_POST['description'],
            'id' => $id
            ];

            $errors = $this->verifForm($room);

            if (empty($errors)) {
                $this->roomManager->update($room);
                header('Location: /calendar/month/' .
                    $this->calendar->month . '/' .
                    $this->calendar->year . '/' .
                    $this->calendar->week);
                exit;
            } else {
                $this->setMessages($errors);
                $this->setPostData($room);
                $this->setAction('EditRoom' .$id);
                return $this->month();
            }
        }
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $room = ['name' => $_POST['name'],
                    'capacity' => $_POST['capacity'],
                    'description' => $_POST['description']
            ];
            $errors = $this->verifForm($room);
            if (empty($errors)) {
                $this->roomManager->insert($room);
                header('Location: /calendar/month/' .
                    $this->calendar->month . '/' .
                    $this->calendar->year . '/' .
                    $this->calendar->week);
                exit;
            } else {
                $messages = $errors;
                $this->setMessages($messages);
                $this->setPostData($room);
                $this->setAction('room');
                return $this->month();
            }
        }
    }

    public function delete(int $id)
    {
        $roomManager = new RoomManager();
        $roomManager->delete($id);
        header('Location: /calendar/month/' .
            $this->calendar->month . '/' .
            $this->calendar->year . '/' .
            $this->calendar->week);
        exit;
    }

    private function verifForm($room)
    {
        $errors= [];

        if (empty($room['name'])) {
            $errors['name'] = 'You should enter a room name';
        }
        if (empty($room['capacity'])) {
            $errors['capacity'] = 'You should enter a room capacity';
        }
        if (empty($room['description'])) {
            $errors['description'] = 'You should add a description';
        }
        return $errors;
    }
}
