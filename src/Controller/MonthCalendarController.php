<?php


namespace App\Controller;

use Twig\Environment;
use App\Service\Calendar;
use \DateTime;
use \Exception;

class MonthCalendarController extends AbstractController
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

    /**
     * Donne le nombre de semaine a afficher
     * @return int nombre semaines dans le mois mois
     * @throws Exception
     */
    public function getWeeks(): int
    {
        $start = $this->getStartingDay();
        $end = (clone $start)->modify('+1 month -1 day');
        $weeks = intval($end->format('W')) - intval($start->format('W'));
        if ($weeks < 0) {
            $weeks = intval($end->format('W'));
        }
        return $weeks;
    }

    /**
     * définit par quel jour commence le mois
     * @return DateTime
     * @throws Exception
     */
    public function getStartingDay(): DateTime
    {
        return new DateTime("{$this->calendar->year}-{$this->calendar->month}-01");
    }

    public function getStartingDayType()
    {
        return $this->getStartingDay()->format('N');
    }

    /**
     * donne le numéro de jour du dernier lundi
     * @return string
     * @throws Exception
     */
    public function getFirstMonday()
    {
        return $this->getStartingDay()->modify('last monday')->format('Y-m-d');
    }

    /**
     * renvoie au mois suivant
     * @return string next month well formated
     * @throws Exception
     */
    public function nextMonth()
    {

        $date = new DateTime();
        $date ->setISOdate($this->calendar->year, $this->calendar->week);
        return $date->modify('+1 month')->format('m/Y/W');
    }

    /**
     * renvoie au mois précédent
     * @return string previous mounth well formated
     * @throws Exception
     */
    public function previousMonth()
    {
        $date = new DateTime();
        $date ->setISOdate($this->calendar->year, $this->calendar->week);
        return $date->modify('-1 month')->format('m/Y/W');
    }

    /**
     * retourne mois a afficher en toutes lettres
     * @return string
     */
    public function fullDate(): string
    {
        return $this->calendar->months[$this->calendar->month -1] . ' ' . $this->calendar->year;
    }

    public function show($month = null, $year = null, $week = null)
    {
        $this->setCalendar(new Calendar($month, $year, $week));

        return $this->twig->render('monthCalendar.html.twig', [
                                                                'fullDate' => $this->fullDate(),
                                                                'nbWeeks' => $this->getWeeks(),
                                                                'firstMonday' => $this->getFirstMonday(),
                                                                'firstMonthDay' => $this->getStartingDay(),
                                                                'firstMonthDayType' => $this->getStartingDayType(),
                                                                'days' => $this->calendar->days,
                                                                'next' => $this->nextMonth(),
                                                                'previous' => $this->previousMonth()
                                                                ]);
    }
}
