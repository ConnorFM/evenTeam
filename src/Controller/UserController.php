<?php

namespace App\Controller;

use App\Model\UserManager;
use App\Service\Session;

class UserController extends CalendarController
{
  // Display every user
    public function index()
    {
        $userManager = new UserManager();
        $users = $userManager->selectFirstname();
        $usersjson = json_encode($users);
        return $this->twig->render('Users/user.html.twig', ['users' => $users,
                                                            'usersjson' => $usersjson
                                                            ]);
    }

    public function show($id)
    {
        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);

        return $this->twig->render('Users/show.html.twig', ['user' => $user]);
    }
  // Edit the user
    public function edit($id)
    {
        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              $user['firstname'] = $_POST['firstname'];
              $user['lastname'] = $_POST['lastname'];
              $user['email'] = $_POST['email'];
              $user['status_id'] = $_POST['status_id'];
              $user['image'] = $_POST['image'];
              $user['password'] = $_POST['password'];
              $userManager->update($user);
        }

          return $this->twig->render('_editUser.html.twig', ['user' => $user]);
    }
  // Delete a user with the id
    public function delete($id)
    {
        $userManager = new userManager();
        $userManager->delete((int)$id);
        header('Location:/calendar/week');
    }
    // Create a user
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userManager = new userManager();
            $user = [
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'email' => $_POST['email'],
            'status_id' => $_POST['status_id'],
            'image' => $_POST['image'],
            'password' => $_POST['password']
            ];
            if (!empty($_POST['email']) && $_POST['email'] == $userManager->getEmail($_POST['email'])['email']) {
                echo "Email déja existant";
            } elseif (empty($_POST['email'])) {
                echo "Veuillez renseigner votre email";
            } else {
                $userManager->insert($user);
                header('Location:/calendar/week');
            }
        }
    }


    // Connect the user if the password and the email is ok
    public function connection()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userManager = new UserManager();
            $userBdd = $userManager->getLog($_POST['email']);
            if (($userBdd['email'] == $_POST['email']) && ($userBdd['password'] == $_POST['password'])) {
                $session = new Session;
                $session->createSession(
                    $userBdd['id'],
                    $userBdd['status_id'],
                    $userBdd['lastname'],
                    $userBdd['firstname']
                );
                header('Location:/user/index');
            } else {
                echo "Mot de passe incorect ou email inexistant";
            }
        }
    }

    // Disconnect the user and redirect to login page
    public function logOut()
    {
        session_destroy();
        header('Location:/user/login');
    }

    // Display the login page
    public function logIn()
    {
        if (!empty($_SESSION)) {
            header('Location: /calendar/month');
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userManager = new UserManager();
            $userBdd = $userManager->getLog($_POST['email']);
            if ((!empty($_POST['email']) && $userBdd['email'] == $_POST['email'])
                && (!empty($_POST['password']) && $userBdd['password'] == $_POST['password'])) {
                $session = new Session;
                $session->createSession(
                    $userBdd['id'],
                    $userBdd['status_id'],
                    $userBdd['lastname'],
                    $userBdd['firstname']
                );
                header('Location: /calendar/week');
                exit();
            } else {
                $this->twig->addGlobal("errorConnection", true);
            }
        }
        return $this->twig->render('Users/login.html.twig');
    }
}
