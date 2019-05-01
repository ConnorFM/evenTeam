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
            'image' => $_POST['image'],
            'room_id' => $id
            ];

            $errors = $this->verifForm($room);

            if (empty($errors)) {
                $this->roomManager->update($room);
                $date = new \DateTime();
                $month = (clone $date)->format('m');
                $year = (clone $date)->format('Y');
                $week = (clone $date)->format('W');
                header("Location: /calendar/month/$month/$year/$week");
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

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $room = ['name' => $_POST['name'],
                    'capacity' => $_POST['capacity'],
                    'description' => $_POST['description'],
                    'image' => $_POST['image']
            ];
            $errors = $this->verifForm($room);
            if (empty($errors)) {
                $this->roomManager->insert($room);
                $date = new \DateTime();
                $month = (clone $date)->format('m');
                $year = (clone $date)->format('Y');
                $week = (clone $date)->format('W');
                header("Location: /calendar/month/$month/$year/$week");
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
        header('Location:/calendar/month');
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
        if (empty($room['image'])) {
            $errors['image'] = 'You should put an image';
        }
        return $errors;
    }
}
