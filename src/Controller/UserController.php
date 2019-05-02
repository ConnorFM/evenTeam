<?php

namespace App\Controller;

use App\Model\UserManager;
use App\Service\Session;

class UserController extends CalendarController
{
    protected $userManager;

    public function __construct()
    {
        parent::__construct();
        $this->userManager = new UserManager();
    }

  // Edit the user
    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              $user['firstname'] = $_POST['firstname'];
              $user['lastname'] = $_POST['lastname'];
              $user['email'] = $_POST['email'];
              $user['status_id'] = $_POST['status_id'];
              $user['password'] = $_POST['password'];
              $user['id'] = $id;

            $errors = $this->verifForm($user);

            if (empty($errors)) {
                $this->userManager->update($user);

                if ($_SESSION['id'] == $id) {
                    $session = new Session();
                    $session->createSession($user);
                }

                header('Location: /calendar/month/' .
                    $this->calendar->month . '/' .
                    $this->calendar->year . '/' .
                    $this->calendar->week);
                exit;
            } else {
                $this->setMessages($errors);
                $this->setPostData($user);
                $this->setAction('EditUser' .$id);
                return $this->month();
            }
        }
    }

  // Delete a user with the id
    public function delete($id)
    {
        $this->userManager->delete((int)$id);
        if ($id == $_SESSION['id']) {
            $this->logOut();
        }
        header('Location: /calendar/month/' .
            $this->calendar->month . '/' .
            $this->calendar->year . '/' .
            $this->calendar->week);
        exit;
    }

    // Create a user
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = [
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'email' => $_POST['email'],
                'status_id' => $_POST['status_id'],
                'password' => $_POST['password']
            ];

            $errors = $this->verifForm($user);
            if ($_POST['email'] == $this->userManager->getOneByEmail($_POST['email'])['email']) {
                $errors['email'] = "This email already exist";
            }

            if (empty($errors)) {
                $this->userManager->insert($user);
                // Create the Transport
                $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
                  ->setUsername('noreply.eventeam@gmail.com')
                  ->setPassword('evenTeam2019')
                ;

               // Create the Mailer using your created Transport
                $mailer = new \Swift_Mailer($transport);
                // Create a message


                $message = (new \Swift_Message('You are now a member of Eventeam'))
                ->setFrom(['noreply@eventeam.com' => 'Eventeam'])
                ->setTo(['noreply.eventeam@gmail.com'])
                ->setBCC([$user['email']])
                ->setBody(
                    '<html>' .
                         ' <head></head>' .
                         ' <body>' .
                         'Welcome to Eventeam!' . '<br />' .'<br />' .
                         'You are now a member of evenTeam.
                         You can now log in with your email adress
                         and the password you have been attributed to: '
                         . '<br />' . '<br />' .
                         '<span style="font-weight: bold;">' .
                         $user['password'] . '</span><br />' . '<br />'.
                         'See you soon on <a href="eventeam.com">Eventeam.</a>' .
                         '</body>' .
                         '</html>',
                    'text/html'
                );

            // Send the message
                $mailer->send($message);

                header('Location: /calendar/month/' .
                    $this->calendar->month . '/' .
                    $this->calendar->year . '/' .
                    $this->calendar->week);
                exit;
            } else {
                $messages = $errors;
                $this->setMessages($messages);
                $this->setPostData($user);
                $this->setAction('user');
                return $this->month();
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
    public function login()
    {
        if (!empty($_SESSION)) {
            header('Location: /calendar/month/' .
                $this->calendar->month . '/' .
                $this->calendar->year . '/' .
                $this->calendar->week);
            exit;
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userManager = new UserManager();
            $userBdd = $userManager->getLog($_POST['email']);

            if ((!empty($_POST['email']) && $userBdd['email'] == $_POST['email'])
                && (!empty($_POST['password']) && $userBdd['password'] == $_POST['password'])) {
                $session = new Session;
                $session->createSession($userBdd);

                header('Location: /calendar/month/' .
                                        $this->calendar->month . '/' .
                                        $this->calendar->year . '/' .
                                        $this->calendar->week);
                exit;
            } else {
                $this->twig->addGlobal("errorConnection", true);
            }
        }

        return $this->twig->render('Home/login.html.twig');
    }

    private function verifForm(array $user)
    {
        $errors = [];

        if (empty($user['firstname'])) {
            $errors['firstname'] = "Please enter a firstname";
        }
        if (empty($user['lastname'])) {
            $errors['lastname'] = "Please enter a lastname";
        }
        if (empty($_POST['email'])) {
            $errors['email'] = "Please enter an email";
        }
        if (empty($user['status_id'])) {
            $errors['status'] = "Please select a user's status";
        }
        if (empty($user['password'])) {
            $errors['password'] = "Please enter a password";
        }

        return $errors;
    }
}
