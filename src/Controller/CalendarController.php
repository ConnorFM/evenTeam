<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use Twig\Environment;
use App\Service\Calendar;
use \DateTime;
use \Exception;

/**
 * Class ItemController
 *
 */
class CalendarController extends AbstractController
{
    private $calendar;

    public function __construct($month = null, $year = null)
    {
        parent::__construct();
        $this->setCalendar(new Calendar($month, $year));
    }

    /**
     * @return mixed
     */
    private function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * @param mixed $calendar
     */
    private function setCalendar($calendar): void
    {
        $this->calendar = $calendar;
    }


    public function month($month = null, $year = null, $week = null)
    {
        $this->setCalendar(new Calendar($month, $year, $week));
        return $this->twig->render('monthCalendar.html.twig', [
                                                                'fullDate' => $this->getCalendar()->fullDate(),
                                                                'next' => $this->getCalendar()->nextMonth(),
                                                                'previous' => $this->getCalendar()->previousMonth(),
                                                                'calendar' => $this->getCalendar()->generateMonth(),
                                                                'days' => $this->getCalendar()->days
                                                                ]);
    }


    public function week($month = null, $year = null, $week = null)
    {
        $this->setCalendar(new Calendar($month, $year, $week));

        return $this->twig->render('weekCalendar.html.twig', [
                                                                    'fullDate' => $this->getCalendar()->getTitle(),
                                                                    'daysOfWeek' => $this->getCalendar()->daysOfWeek(),
                                                                    'next' => $this->getCalendar()->nextWeek(),
                                                                    'previous' => $this->getCalendar()->previousWeek(),
                                                                    'calendar' => $this->getCalendar()->generateWeek()
                                                                    ]);
    }
}
