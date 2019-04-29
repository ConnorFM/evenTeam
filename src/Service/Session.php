<?php

namespace App\Service;

class Session
{
    public function createSession($user)
    {
        $_SESSION['id'] = $user['id'];
        $_SESSION['status'] =$user['status_id'];
        $_SESSION['lastname'] =$user['lastname'];
        $_SESSION['firstname'] =$user['firstname'];
    }

    public function isConnected()
    {
        if (empty($_SESSION)) {
            return "Not connected";
        }
        if ($_SESSION['status'] == 1) {
            return "admin";
        } else {
            return "user";
        }
    }
}
