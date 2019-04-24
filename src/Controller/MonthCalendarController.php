<?php


namespace App\Controller;

use Twig\Environment;
use App\Service\Calendar;
use \DateTime;
use \Exception;

class MonthCalendarController extends AbstractController
{

    private $calendar;


    public function __construct($month = null, $year = null, $week = null)
    {
        parent::__construct();
        $this->setCalendar(new Calendar($month = null, $year, $week));
    }

    /**
     * @return mixed
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * @param mixed $calendar
     */
    public function setCalendar($calendar): void
    {
        $this->calendar = $calendar;
    }




    public function show($month = null, $year = null, $week = null)
    {
        $this->setCalendar(new Calendar($month, $year, $week));

        return $this->twig->render('monthCalendar.html.twig', [
                                                                'fullDate' => $this->getCalendar()->fullDate(),
                                                                'nbWeeks' => $this->getCalendar()->getWeeks(),
                                                                'firstMonday' => $this->getCalendar()->getFirstMonday(),
                                                                'firstMonthDay' => $this->getCalendar()->getStartingDay(),
                                                                'firstMonthDayType' => $this->getCalendar()->getStartingDayType(),
                                                                'days' => $this->getCalendar()->days,
                                                                'next' => $this->getCalendar()->nextMonth(),
                                                                'previous' => $this->getCalendar()->previousMonth()
                                                                ]);
    }
}
