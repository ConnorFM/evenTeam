<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\EventManager;

/**
 * Class EventController
 */
class EventController extends CalendarController
{
  /**
   * Display the created event on the 'weekCalendar.html.twig'
   * page with a success message, or display the same form with errors messages.
   *
   * This method will insert into 'events' table the event inputs and selected options.
   *
   * @return mixed
   */
    public function addEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Setting aliases array
            $events = [
                'eventName'         => $_POST['eventName'],
                'eventDescription'  => $_POST['eventDescription'],
                'eventBeginYear'    => $_POST['eventBeginYear'],
                'eventBeginMonth'   => $_POST['eventBeginMonth'],
                'eventBeginDay'     => $_POST['eventBeginDay'],
                'eventBeginHour'    => $_POST['eventBeginHour'],
                'eventEndYear'      => $_POST['eventEndYear'],
                'eventEndMonth'     => $_POST['eventEndMonth'],
                'eventEndDay'       => $_POST['eventEndDay'],
                'eventEndHour'      => $_POST['eventEndHour'],
                'userId'            => $_POST['user']
            ];

            if (empty($_POST['eventRoom'])) {
                $events['eventRoom'] = null;
            } else {
                $events['eventRoom'] = $_POST['eventRoom'];
            }



            // Testing $errors in 'verifEvent' method
            $errors = $this->verifEvent($events);

            // Condition verify the errors array is empty
            if (empty($errors)) {
                $validEvent = [
                    "name"        => $events['eventName'],
                    "date_start"  => $events['eventBeginYear'] . "-" .
                                     $events['eventBeginMonth'] . "-" .
                                     $events['eventBeginDay'] . " " .
                                     $events['eventBeginHour'] . ":00",
                    "date_end"    => $events['eventEndYear'] . "-" .
                                     $events['eventEndMonth'] . "-" .
                                     $events['eventEndDay'] . " " .
                                     $events['eventEndHour'] . ":00",
                    "room_id"     => $events['eventRoom'],
                    "description" => $events['eventDescription'],
                    "user_id"     => $events['userId']
                ];

                $eventManager = new EventManager();
                $eventManager->insert($validEvent);

                $getMail = new EventManager;
                // Create the Transport
                $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
                  ->setUsername('noreply.eventeam@gmail.com')
                  ->setPassword('evenTeam2019')
                ;

               // Create the Mailer using your created Transport
                $mailer = new \Swift_Mailer($transport);
                // Create a message

                foreach ($validEvent['user_id'] as $userID) {
                    $userEmail = implode($getMail->getEmail($userID));

                    $message = (new \Swift_Message('Event ' . $validEvent['name'] . ' created'))
                    ->setFrom(['noreply@eventeam.com' => 'Eventeam'])
                    ->setTo(['foucauld.gaudin@gmail.com', $userEmail])
                    ->setBody('Congratulations, your event ' .
                                $events['eventName'] .
                                ' has been created. You can visualize it on your calendar.')
                    ;

                // Send the message
                    $result = $mailer->send($message);
                }

                $this->setMessages("Well done");
                return $this->month($events['eventBeginMonth'], $events['eventBeginYear']);
            } else {
                $messages = $errors;
                $this->setMessages($messages);
                return $this->month();
            }
        }
    }

    /**
    * Fill and stock the errors array.
    *
    * This method will verify if the inputs from '_addEventForm.html.twig' match with the conditions.
    *
    * @param  array $events
    * @return array<string, string>
    */
    private function verifEvent(array $events)
    {
        // Empty errors array initialization
        $errors = [];

        // Testing EVENT NAME input
        if (empty($events['eventName'])) {
            $errors['eventName'] = "Event name is required";
        }
        // Testing EVENT USER input
        if (empty($events['userId'])) {
            $errors['user_id'] = "Select user(s) please";
        }
        // Testing EVENT BEGIN YEAR input
        if (empty($events['eventBeginYear'])) {
            $errors['eventBeginYear'] = "A year is required";
        } elseif (!preg_match("#[0-9]{4}#", $events['eventBeginYear'])) {
            $errors['eventBeginYear'] = "Enter a valid year please ex: 2019";
        }
        // Testing EVENT BEGIN MONTH input
        if (empty($events['eventBeginMonth'])) {
            $errors['eventBeginMonth'] = "A month is required";
        } elseif (!preg_match("#[0-9]{2}#", $events['eventBeginMonth']) || (int)$events['eventBeginMonth'] > 12) {
            $errors['eventBeginMonth'] = "Enter a valid month of beginning please ex: 11 or 07";
        }
        // Testing EVENT BEGIN DAY input
        if (empty($events['eventBeginDay'])) {
            $errors['eventBeginDay'] = "A day is required";
        } elseif (!preg_match("#[0-9]{2}#", $events['eventBeginDay']) || (int)$events['eventBeginDay'] > 31) {
            $errors['eventBeginDay'] = "Enter a valid day of beginning please ex: 31 or 05";
        }
        // Testing EVENT BEGIN HOUR input
        if (empty($events['eventBeginHour'])) {
            $errors['eventBeginHour'] = "An hour is required";
        } elseif (!preg_match("#[0-9]{2}:[0-9]{2}#", $events['eventBeginHour'])) {
            $errors['eventBeginHour'] = "Enter a valid hour of beginning please ex: 05:30 or 16:45";
        }
        // Testing EVENT END YEAR input
        if (empty($events['eventEndYear'])) {
            $errors['eventEndYear'] = "A year is required";
        } elseif (!preg_match("#[0-9]{4}#", $events['eventEndYear'])) {
            $errors['eventEndYear'] = "Enter a valid year please ex: 2019";
        }
        // Testing EVENT END MONTH input
        if (empty($events['eventEndMonth'])) {
            $errors['eventEndMonth'] = "A month is required";
        } elseif (!preg_match("#[0-9]{2}#", $events['eventEndMonth']) || (int)$events['eventEndMonth'] > 12) {
            $errors['eventEndMonth'] = "Enter a valid month please ex: 11 or 07";
        }
        // Testing EVENT END DAY input
        if (empty($events['eventEndDay'])) {
            $errors['eventEndDay'] = "A day is required";
        } elseif (!preg_match("#[0-9]{2}#", $events['eventEndDay']) || (int)$events['eventBeginDay'] > 31) {
            $errors['eventEndDay'] = "Enter a valid day please ex: 31 or 05";
        }
        // Testing EVENT END HOUR input
        if (empty($events['eventEndHour'])) {
            $errors['eventEndHour'] = "An hour is required";
        } elseif (!preg_match("#[0-9]{2}:[0-9]{2}#", $events['eventEndHour'])) {
            $errors['eventEndHour'] = "Enter a valid hour please ex: 05:30 or 16:45";
        }

        // Display errors messages from the errors array
        return $errors;
    }

    public function delete($id)
    {
        $eventManager = new eventManager();
        $eventManager->delete((int)$id);
        header('Location:/calendar/month');
    }
    public function edit($id)
    {
        $events = [
                'eventName'         => $_POST['eventName'],
                'eventDescription'  => $_POST['eventDescription'],
                'eventBeginYear'    => $_POST['eventBeginYear'],
                'eventBeginMonth'   => $_POST['eventBeginMonth'],
                'eventBeginDay'     => $_POST['eventBeginDay'],
                'eventBeginHour'    => $_POST['eventBeginHour'],
                'eventEndYear'      => $_POST['eventEndYear'],
                'eventEndMonth'     => $_POST['eventEndMonth'],
                'eventEndDay'       => $_POST['eventEndDay'],
                'eventEndHour'      => $_POST['eventEndHour'],
                'userId'            => $_POST['user']
            ];

        if (empty($_POST['eventRoom'])) {
            $events['eventRoom'] = null;
        } else {
            $events['eventRoom'] = $_POST['eventRoom'];
        }

            // Testing $errors in 'verifEvent' method
            $errors = $this->verifEvent($events);

            // Condition verify the errors array is empty
        if (empty($errors)) {
            $validEvent = [
                'id'          => $id,
                "name"        => $events['eventName'],
                "date_start"  => $events['eventBeginYear'] . "-" .
                                 $events['eventBeginMonth'] . "-" .
                                 $events['eventBeginDay'] . " " .
                                 $events['eventBeginHour'] . ":00",
                "date_end"    => $events['eventEndYear'] . "-" .
                                 $events['eventEndMonth'] . "-" .
                                 $events['eventEndDay'] . " " .
                                 $events['eventEndHour'] . ":00",
                "room_id"     => $events['eventRoom'],
                "description" => $events['eventDescription'],
                "user_id"     => $events['userId']
            ];

            $eventManager = new EventManager();
            $eventManager->update($validEvent);

            $this->setMessages("Well done");
            return $this->month($events['eventBeginMonth'], $events['eventBeginYear']);
        } else {
            $messages = $errors;
            $this->setMessages($messages);
            return $this->month();
        }
    }
}
