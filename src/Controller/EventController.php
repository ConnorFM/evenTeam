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
class EventController extends AbstractController
{
    /**
     * Display the created event on the 'weekCalendar.html.twig'
     * page with a success message, or display the same form with errors messages.
     *
     * This method will insert into 'events' table the form inputs from '_addEventForm.html.twig'.
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
                'eventRoom'         => $_POST['eventRoom']
            ];
            // take this $errors and test him in 'verifEvent' method
            $errors = $this->verifEvent($events);
            
            // Condition verify the errors array is empty
            if (empty($errors)) {
                $validEvent = [

                    "name"          => $event['eventName'],
                    "date_start"    => $event['eventBeginYear'] . "-" .
                                       $event['eventBeginMonth'] . "-" .
                                       $event['eventBeginDay'] . " " .
                                       $event['eventBeginHour'] . ":00",
                    "date_end"      => $event['eventEndYear'] . "-" .
                                       $event['eventEndMonth'] . "-" .
                                       $event['eventEndDay'] . " " .
                                       $event['eventEndHour'] . ":00",
                    "room_id"       => $event['eventRoom'],
                    "description"   => $event['eventDescription']
                ];

                $eventManager = new EventManager();
                $eventManager->insert($validEvent);
                $messages = "Well done";

                // Display the 'monthCalendar.html.twig' page with a success message
                return $this->twig->render('monthCalendar.html.twig', ['success' => $messages]);
            } else {
                // Display the 'monthCalendar.html.twig' page with errors messages on each mistake
                return $this->twig->render('monthCalendar.html.twig', ['errors' => $errors]);
            }
        }
    }

    /**
     * Stock the errors array in memory.
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
        // Testing EVENT DESCRIPTION input
        if (empty($events['eventDescription'])) {
            $errors['eventDescription'] = "Event description is required";
        }
        // Testing EVENT USER input
        if (empty($events['user_id'])) {
            $errors['user_id'] = "Select a user please";
        }
        // Testing EVENT BEGIN YEAR input
        if (empty($events['eventBeginYear'])) {
            $errors['eventBeginYear'] = "A year is required";
        } elseif (filter_var($events['eventBeginYear'], FILTER_VALIDATE_INT) == false &&
                  !preg_match("#[0-9]{4}#", $events['eventBeginYear'])) {
            $errors['eventBeginYear'] = "Enter a valid year please ex: 2019";
        }
        // Testing EVENT BEGIN MONTH input
        if (empty($events['eventBeginMonth'])) {
            $errors['eventBeginMonth'] = "A month is required";
        } elseif (filter_var($events['eventBeginMonth'], FILTER_VALIDATE_INT) == false &&
                  !preg_match("#[0-9]{2}#", $events['eventBeginMonth'])) {
            $errors['eventBeginMonth'] = "Enter a valid month please ex: 11 or 07";
        }
        // Testing EVENT BEGIN DAY input
        if (empty($events['eventBeginDay'])) {
            $errors['eventBeginDay'] = "A day is required";
        } elseif (filter_var($events['eventBeginDay'], FILTER_VALIDATE_INT) == false &&
                  !preg_match("#[0-9]{2}#", $events['eventBeginDay'])) {
            $errors['eventBeginDay'] = "Enter a valid day please ex: 31 or 05";
        }
        // Testing EVENT BEGIN HOUR input
        if (empty($events['eventBeginHour'])) {
            $errors['eventBeginHour'] = "An hour is required";
        } elseif (filter_var($events['eventBeginHour'], FILTER_VALIDATE_INT) == false &&
                  !preg_match("#[0-9]{2}:[0-9]{2}#", $events['eventBeginHour'])) {
            $errors['eventBeginHour'] = "Enter a valid hour please ex: 05:30 or 16:45";
        }
        // Testing EVENT END YEAR input
        if (empty($events['eventEndYear'])) {
            $errors['eventEndYear'] = "A year is required";
        } elseif (filter_var($events['eventEndYear'], FILTER_VALIDATE_INT) == false &&
                  !preg_match("#[0-9]{4}#", $events['eventEndYear'])) {
            $errors['eventEndYear'] = "Enter a valid year please ex: 2019";
        }
        // Testing EVENT END MONTH input
        if (empty($events['eventEndMonth'])) {
            $errors['eventEndMonth'] = "A month is required";
        } elseif (filter_var($events['eventEndMonth'], FILTER_VALIDATE_INT) == false &&
                  !preg_match("#[0-9]{2}#", $events['eventEndMonth'])) {
            $errors['eventEndMonth'] = "Enter a valid month please ex: 11 or 07";
        }
        // Testing EVENT END DAY input
        if (empty($events['eventEndDay'])) {
            $errors['eventEndDay'] = "A day is required";
        } elseif (filter_var($events['eventEndDay'], FILTER_VALIDATE_INT) == false &&
                  !preg_match("#[0-9]{2}#", $events['eventEndDay'])) {
            $errors['eventEndDay'] = "Enter a valid day please ex: 31 or 05";
        }
        // Testing EVENT END HOUR input
        if (empty($events['eventEndHour'])) {
            $errors['eventEndHour'] = "An hour is required";
        } elseif (filter_var($events['eventEndHour'], FILTER_VALIDATE_INT) == false &&
                  !preg_match("#[0-9]{2}:[0-9]{2}#", $events['eventEndHour'])) {
            $errors['eventEndHour'] = "Enter a valid hour please ex: 05:30 or 16:45";
        }

        // Display errors messages from the errors array
        return $errors;
    }
}
