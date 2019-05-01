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
use App\Model\UserManager;

/**
 * Class EventController
 */
class EventController extends CalendarController
{

    protected $eventManager;
    protected $userManager;

    public function __construct()
    {
        parent::__construct();
        $this->eventManager = new EventManager();
        $this->userManager = new UserManager();
    }


  /**
   * Display the created event on the 'weekCalendar.html.twig'
   * page with a success message, or display the same form with errors messages.
   *
   * This method will insert into 'events' table the event inputs and selected options.
   *
   * @return mixed
   * @throws \Exception
   */
    public function addEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Setting aliases array
            $events = [
                'eventName'         => $_POST['eventName'],
                'eventDescription'  => $_POST['eventDescription'],
                'eventBeginDate'    => $_POST['dateStart'],
                'eventBeginHour'    => $_POST['startHour'],
                'eventEndDate'      => $_POST['dateEnd'],
                'eventEndHour'      => $_POST['endHour'],
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
                    "date_start"  => $events['eventBeginDate'] . " " .
                                     $events['eventBeginHour'] . ":00",
                    "date_end"    => $events['eventEndDate'] . " " .
                                     $events['eventEndHour'] . ":00",
                    "room_id"     => $events['eventRoom'],
                    "description" => $events['eventDescription'],
                    "user_id"     => $events['userId'],
                    "creator"     => $_SESSION['id']
                ];

                $this->eventManager->insert($validEvent);

                // Create the Transport
                $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
                  ->setUsername('noreply.eventeam@gmail.com')
                  ->setPassword('evenTeam2019')
                ;

               // Create the Mailer using your created Transport
                $mailer = new \Swift_Mailer($transport);
                // Create a message

                foreach ($validEvent['user_id'] as $userID) {
                    $userEmail = implode($this->userManager->getEmails($userID));

                    $message = (new \Swift_Message('Event ' . $validEvent['name'] . ' created'))
                    ->setFrom(['noreply@eventeam.com' => 'Eventeam'])
                    ->setTo(['noreply.eventeam@gmail.com'])
                    ->setBCC([$userEmail])
                    ->setBody('Congratulations, your event ' . $events['eventName'] .
                                ' has been created. You can visualize it on your calendar.');

                // Send the message
                    $mailer->send($message);
                }

                $date = new \DateTime($validEvent["date_start"]);
                $month = (clone $date)->format('m');
                $year = (clone $date)->format('Y');
                $week = (clone $date)->format('W');

                header("Location: /calendar/month/$month/$year/$week");
                exit;
            } else {
                $messages = $errors;
                $this->setMessages($messages);
                $this->setPostData($_POST);
                $this->setAction('event');
                return $this->month();
            }
        }
    }

    public function delete($id)
    {
        $this->eventManager->delete((int)$id);
        header('Location: /calendar/month/' .
            $this->calendar->month . '/' .
            $this->calendar->year . '/' .
            $this->calendar->week);
        exit;
    }
    public function edit($id)
    {
        $events = [
            'eventName'         => $_POST['eventName'],
            'eventDescription'  => $_POST['eventDescription'],
            'eventBeginDate'    => $_POST['dateStart'],
            'eventBeginHour'    => $_POST['startHour'],
            'eventEndDate'      => $_POST['dateEnd'],
            'eventEndHour'      => $_POST['endHour'],
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
                "date_start"  => $events['eventBeginDate'] . " " .
                                 $events['eventBeginHour'] . ":00",
                "date_end"    => $events['eventEndDate'] . " " .
                                 $events['eventEndHour'] . ":00",
                "room_id"     => $events['eventRoom'],
                "description" => $events['eventDescription'],
                "user_id"     => $events['userId']
            ];

            $eventManager = new EventManager();
            $eventManager->update($validEvent);

            $date = new \DateTime($validEvent["date_start"]);
            $month = (clone $date)->format('m');
            $year = (clone $date)->format('Y');
            $week = (clone $date)->format('W');

            header("Location: /calendar/month/$month/$year/$week");
        } else {
            $messages = $errors;
            $this->setMessages($messages);
            $this->setPostData($_POST);
            $this->setAction('event');
            return $this->month();
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
        //test EVENT BEGIN DATE
        if (empty($events['eventBeginDate'])) {
            $errors['eventBeginDate'] = "Please select a starting date";
        }
        // Testing EVENT BEGIN HOUR input
        if (empty($events['eventBeginHour'])) {
            $errors['eventBeginHour'] = "An hour is required";
        } elseif (!preg_match("#[0-9]{2}:[0-9]{2}#", $events['eventBeginHour'])) {
            $errors['eventBeginHour'] = "Enter a valid hour of beginning please ex: 05:30 or 16:45";
        }
        //test EVENT END DATE
        if (empty($events['eventEndDate'])) {
            $errors['eventEndDate'] = "Please select a ending date";
        }
        // Testing EVENT END HOUR input
        if (empty($events['eventEndHour'])) {
            $errors['eventEndHour'] = "An hour is required";
        } elseif (!preg_match("#[0-9]{2}:[0-9]{2}#", $events['eventEndHour'])) {
            $errors['eventEndHour'] = "Enter a valid hour of ending please ex: 05:30 or 16:45";
        }

        // Display errors messages from the errors array
        return $errors;
    }
}
