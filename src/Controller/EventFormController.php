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
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			// setting aliases
			$eventName          => $_POST['eventName'];
			$eventDescription   => $_POST['eventDescription'];
			$eventBeginYear     => $_POST['eventBeginYear'];
			$eventBeginMonth    => $_POST['eventBeginMonth'];
			$eventBeginDay      => $_POST['eventBeginDay'];
			$eventBeginHour     => $_POST['eventBeginHour'];
			$eventEndYear       => $_POST['eventEndYear'];
			$eventEndMonth      => $_POST['eventEndMonth'];
			$eventEndDay        => $_POST['eventEndDay'];
			$eventEndHour       => $_POST['eventEndHour'];

			// error array initialized at empty
			$error=[];

			// testing EVENT NAME
			if(empty($_POST["eventName"]))
			{
                $error['eventNameErr'] = "Event name is required";
            }
            // testing EVENT DESCRIPTION
            if(empty($_POST["eventDescription"]))
            {
                $error['eventDescriptionErr'] = "Event description is required";
            }
            // testing EVENT BEGIN YEAR
            if(empty($_POST["eventBeginYear"]))
            {
                $error['eventBeginYearErr'] = "a year is required";
            }
            elseif(filter_var($eventBeginYear, FILTER_VALIDATE_INT) == false and preg_match("#[0-9]{4}#"))
    		{
                $error['eventBeginYearErr'] = "enter a valid year please ex: 2019";
            }
            // testing EVENT BEGIN MONTH
            if(empty($_POST["eventBeginMonth"]))
            {
                $error['eventBeginMonthErr'] = "a month is required";
            }
            elseif(filter_var($eventBeginMonth, FILTER_VALIDATE_INT) == false and preg_match("#[0-9]{2}#"))
    		{
                $error['eventBeginMonthErr'] = "enter a valid month please ex: 11 or 07";
            }
            // testing EVENT BEGIN DAY
            if(empty($_POST["eventBeginDay"]))
            {
                $error['eventBeginDay'] = "a day is required";
            }
            elseif(filter_var($eventBeginDay, FILTER_VALIDATE_INT) == false and preg_match("#[0-9]{2}#"))
    		{
                $error['eventBeginDayErr'] = "enter a valid day please ex: 31 or 05";
            }
            // testing EVENT BEGIN HOUR
            if(empty($_POST["eventBeginHour"]))
            {
          	$error['eventBeginHourErr'] = "a month is required";
            }
            elseif(filter_var($eventBeginMonth, FILTER_VALIDATE_INT) == false and preg_match("#[0-9]{2}#"))
    		{
                $error['eventBeginMonthErr'] = "enter a valid month please ex: 11 or 02";
            }




            // When evey inputs are correct, click on submit button redirect to calendar.html.twig
            if(empty($error))
            {
                // appel à la requête préparée "public function insert(array $item): int" de ItemManager.php
          	

              	// Redirection to calendar.html.twig
              	header("location: /calendar.html.twig");
                exit;
            }
            else
            {
                // Return view of the Form with error messages
                return $this->twig->render('_addEventForm.html.twig', ['errors' => $error]);
            }
		}
	}
}