<?php


namespace App\Controller;

use App\Model\RoomManager;

class RoomController extends AbstractController
{

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


    public function edit(int $id): string
    {
        $roomManager = new RoomManager();
        $room = $roomManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $room['title'] = $_POST['title'];
            $roomManager->update($room);
        }
    }

    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $room = ['name' => $_POST['name'],
                    'capacity' => $_POST['capacity'],
                    'image' => $_POST['image']
            ];

            $errors = $this->verifForm($room);

            if (empty($errors)) {
                $roomManager = new RoomManager();
                $roomManager->insert($room);

                return 'success';
            } else {
                return $errors;
            }
        }

        return $this->twig->render('room/add.html.twig');
    }

    public function delete(int $id)
    {
        $roomManager = new RoomManager();
        $roomManager->delete($id);
        header('Location:'.$_SERVER['PHP_SELF']);
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
        if (empty($room['image'])) {
            $errors['image'] = 'You should put an image';
        }
        return $errors;
    }
}
