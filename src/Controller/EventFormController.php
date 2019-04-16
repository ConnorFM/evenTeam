<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\ItemManager;

/**
 * Class EventFormController
 *
 */
class EventFormController extends AbstractController
{
    // validation function of add event form (empty or not and respecting filters)
    public function valid()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // setting aliases
            $eventName          = $_POST['eventName'];
            $eventDescription   = $_POST['eventDescription'];
            $eventBeginYear     = $_POST['eventBeginYear'];
            $eventBeginMonth    = $_POST['eventBeginMonth'];
            $eventBeginDay      = $_POST['eventBeginDay'];
            $eventBeginHour     = $_POST['eventBeginHour'];
            $eventEndYear       = $_POST['eventEndYear'];
            $eventEndMonth      = $_POST['eventEndMonth'];
            $eventEndDay        = $_POST['eventEndDay'];
            $eventEndHour       = $_POST['eventEndHour'];

            // error array initialized at empty
            $error=[];

            // testing EVENT NAME
            if (empty($eventName)) {
                $error['eventNameErr'] = "Event name is required";
            }
            // testing EVENT DESCRIPTION
            if (empty($eventDescription)) {
                $error['eventDescriptionErr'] = "Event description is required";
            }
            // testing EVENT BEGIN YEAR
            if (empty($eventBeginYear)) {
                $error['eventBeginYearErr'] = "a year is required";
            } elseif (filter_var($eventBeginYear, FILTER_VALIDATE_INT) == false &&
                      preg_match("#[0-9]{4}#", $eventBeginYear)) {
                $error['eventBeginYearErr'] = "enter a valid year please ex: 2019";
            }
            // testing EVENT BEGIN MONTH
            if (empty($eventBeginMonth)) {
                $error['eventBeginMonthErr'] = "a month is required";
            } elseif (filter_var($eventBeginMonth, FILTER_VALIDATE_INT) == false &&
                      preg_match("#[0-9]{2}#", $eventBeginMonth)) {
                $error['eventBeginMonthErr'] = "enter a valid month please ex: 11 or 07";
            }
            // testing EVENT BEGIN DAY
            if (empty($eventBeginDay)) {
                $error['eventBeginDay'] = "a day is required";
            } elseif (filter_var($eventBeginDay, FILTER_VALIDATE_INT) == false &&
                      preg_match("#[0-9]{2}#", $eventBeginDay)) {
                $error['eventBeginDayErr'] = "enter a valid day please ex: 31 or 05";
            }
            // testing EVENT BEGIN HOUR
            if (empty($eventBeginHour)) {
                $error['eventBeginHourErr'] = "an hour is required";
            } elseif (filter_var($eventBeginHour, FILTER_VALIDATE_INT) == false &&
                      preg_match("#[0-9]{2}#", $eventBeginHour)) {
                $error['eventBeginHourErr'] = "enter a valid hour please ex: 05:30 or 16:45";
            }
            // testing EVENT END YEAR
            if (empty($eventEndYear)) {
                $error['eventEndYearErr'] = "a year is required";
            } elseif (filter_var($eventEndYear, FILTER_VALIDATE_INT) == false &&
                      preg_match("#[0-9]{4}#", $eventEndYear)) {
                $error['eventEndYearErr'] = "enter a valid year please ex: 2019";
            }
            // testing EVENT END MONTH
            if (empty($eventEndMonth)) {
                $error['eventEndMonthErr'] = "a month is required";
            } elseif (filter_var($eventEndMonth, FILTER_VALIDATE_INT) == false &&
                      preg_match("#[0-9]{2}#", $eventEndMonth)) {
                $error['eventEndMonthErr'] = "enter a valid month please ex: 11 or 07";
            }
            // testing EVENT END DAY
            if (empty($eventEndDay)) {
                $error['eventEndDay'] = "a day is required";
            } elseif (filter_var($eventEndDay, FILTER_VALIDATE_INT) == false &&
                      preg_match("#[0-9]{2}#", $eventEndDay)) {
                $error['eventEndDayErr'] = "enter a valid day please ex: 31 or 05";
            }
            // testing EVENT END HOUR
            if (empty($eventEndHour)) {
                $error['eventEndHourErr'] = "an hour is required";
            } elseif (filter_var($eventEndHour, FILTER_VALIDATE_INT) == false &&
                      preg_match("#[0-9]{2}#", $eventEndHour)) {
                $error['eventEndHourErr'] = "enter a valid hour please ex: 05:30 or 16:45";
            }




            // When evey inputs are correct, click on submit button redirect to calendar.html.twig
            if (empty($error)) {
                // appel à la requête préparée "public function insert(array $item): int" de EventManager.php
                $messages = "Well done";

                // Redirection to calendar.html.twig
                return $this->twig->render('calendar.html.twig', ['success' => $messages]);
            } else {
                // Return view of the Form with error messages
                return $this->twig->render('calendar.html.twig', ['errors' => $error]);
            }
        }
    }
}
