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
     * Display the created event on the 'weekCalendar.html.twig' page with a success message.
     *
     * This method will insert into 'events' table the form inputs from '_addEventForm.html.twig'.
     *
     * @return mixed
     */
    public function addEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Setting aliases array
            $event = [
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

            // Errors array initialized at empty
            $errors = $this->verifEvent($event);

            // Condition which verify the errors array as empty
            if (empty($errors)) {
                $validEvent = [
                    "name"          => $event['eventName'],
                    "date_start"    => $event['eventBeginYear'] . "-" . $event['eventBeginMonth'] . "-" . $event['eventBeginDay'] . " " . $event['eventBeginHour'] . ":00",
                    "date_end"      => $event['eventEndYear'] . "-" . $event['eventEndMonth'] . "-" . $event['eventEndDay'] . " " . $event['eventEndHour'] . ":00",
                    "room_id"       => $event['eventRoom'],
                    "description"   => $event['eventDescription']
                ];

                $eventManager = new EventManager();
                $eventManager -> insert($validEvent);
                $messages = "Well done";

                // Display the 'weekCalendar.html.twig' page with a success message
                return $this->twig->render('weekCalendar.html.twig', ['success' => $messages]);
            } else {
                // Display the 'weekCalendar.html.twig' page with errors messages on each mistake
                return $this->twig->render('weekCalendar.html.twig', ['errors' => $errors]);
            }
        }
    }

    /**
     * Display the 'weekCalendar.html.twig' page with errors messages.
     *
     * This method will verify if the inputs from '_addEventForm.html.twig' match with the conditions.
     *
     * @param  array $event
     * @return array<string, string>
     */
    private function verifEvent(array $event)
    {
        $errors = [];

        // Testing EVENT NAME input
        if (empty($event['eventName'])) {
            $errors['eventName'] = "Event name is required";
        }
        // Testing EVENT DESCRIPTION input
        if (empty($event['eventDescription'])) {
            $errors['eventDescription'] = "Event description is required";
        }
        // Testing EVENT BEGIN YEAR input
        if (empty($event['eventBeginYear'])) {
            $errors['eventBeginYear'] = "A year is required";
        } elseif (filter_var($event['eventBeginYear'], FILTER_VALIDATE_INT) == false &&
                  preg_match("#[0-9]{4}#", $event['eventBeginYear'])) {
            $errors['eventBeginYear'] = "Enter a valid year please ex: 2019";
        }
        // Testing EVENT BEGIN MONTH input
        if (empty($event['eventBeginMonth'])) {
            $errors['eventBeginMonth'] = "A month is required";
        } elseif (filter_var($event['eventBeginMonth'], FILTER_VALIDATE_INT) == false &&
                  preg_match("#[0-9]{2}#", $event['eventBeginMonth'])) {
            $errors['eventBeginMonth'] = "Enter a valid month please ex: 11 or 07";
        }
        // Testing EVENT BEGIN DAY input
        if (empty($event['eventBeginDay'])) {
            $errors['eventBeginDay'] = "A day is required";
        } elseif (filter_var($event['eventBeginDay'], FILTER_VALIDATE_INT) == false &&
                  preg_match("#[0-9]{2}#", $event['eventBeginDay'])) {
            $errors['eventBeginDay'] = "Enter a valid day please ex: 31 or 05";
        }
        // Testing EVENT BEGIN HOUR input
        if (empty($event['eventBeginHour'])) {
            $errors['eventBeginHour'] = "An hour is required";
        } elseif (filter_var($event['eventBeginHour'], FILTER_VALIDATE_INT) == false &&
                  preg_match("#[0-9]{2}#", $event['eventBeginHour'])) {
            $errors['eventBeginHour'] = "Enter a valid hour please ex: 05:30 or 16:45";
        }
        // Testing EVENT END YEAR input
        if (empty($event['eventEndYear'])) {
            $errors['eventEndYear'] = "A year is required";
        } elseif (filter_var($event['eventEndYear'], FILTER_VALIDATE_INT) == false &&
                  preg_match("#[0-9]{4}#", $event['eventEndYear'])) {
            $errors['eventEndYear'] = "Enter a valid year please ex: 2019";
        }
        // Testing EVENT END MONTH input
        if (empty($event['eventEndMonth'])) {
            $errors['eventEndMonth'] = "A month is required";
        } elseif (filter_var($event['eventEndMonth'], FILTER_VALIDATE_INT) == false &&
                  preg_match("#[0-9]{2}#", $event['eventEndMonth'])) {
            $errors['eventEndMonth'] = "Enter a valid month please ex: 11 or 07";
        }
        // Testing EVENT END DAY input
        if (empty($event['eventEndDay'])) {
            $errors['eventEndDay'] = "A day is required";
        } elseif (filter_var($event['eventEndDay'], FILTER_VALIDATE_INT) == false &&
                  preg_match("#[0-9]{2}#", $event['eventEndDay'])) {
            $errors['eventEndDay'] = "Enter a valid day please ex: 31 or 05";
        }
        // Testing EVENT END HOUR input
        if (empty($event['eventEndHour'])) {
            $errors['eventEndHour'] = "An hour is required";
        } elseif (filter_var($event['eventEndHour'], FILTER_VALIDATE_INT) == false &&
                  preg_match("#[0-9]{2}#", $event['eventEndHour'])) {
            $errors['eventEndHour'] = "Enter a valid hour please ex: 05:30 or 16:45";
        }

        // Display errors messages of the errors array
        return $errors;
    }
}
