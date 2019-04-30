<?php


namespace App\Controller;

use App\Model\RoomManager;

class RoomController extends CalendarController
{
    protected $roomManager;


    public function index()
    {
        $roomManager = new RoomManager();
        $rooms = $roomManager->selectAll();
        return $rooms;
    }

    public function show(int $id)
    {
        $roomManager = new RoomManager();
        $room = $roomManager->selectOneById($id);
        return $room;
    }


    public function edit($id)
    {
        $roomManager = new RoomManager();
        $room = $roomManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $room['name'] = $_POST['name'];
            $room['capacity'] = $_POST['capacity'];
            $room['description'] = $_POST['description'];
            $room['image'] = $_POST['image'];
            $roomManager->update($room);
        }

        header('Location:/calendar/month');
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
                $roomManager = new RoomManager();
                $roomManager->insert($room);

                $date =  new \DateTime();

                $messages = "Well done";
                $this->setMessages($messages);
                return $this->month();
            } else {
                $this->setMessages($errors);
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
