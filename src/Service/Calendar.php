<?php


namespace App\Service;

use DateTime;
use Exception;

class Calendar
{
    public $days = [
        0 => 'Lundi',
        1 => 'Mardi',
        2 => 'Mercredi',
        3 => 'Jeudi',
        4 => 'Vendredi',
        5 => 'Samedi',
        6 => 'Dimanche'
    ];
    public $months = [
        'January',
        'Febuary',
        'Mars',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];
    public $weeks = [0,1,2,3,4,5,6];
    public $month;
    public $year;
    public $week;

    public function __construct($month = null, $year = null, $week = null)
    {
        if ($month != null) {
            $this->month = $month;
        } else {
            $this->month = intval(date('m'));
        }
        if ($year != null) {
            $this->year = $year;
        } else {
            $this->year = intval(date('Y'));
        }
        if ($month != null) {
            $this->week = $week;
        } else {
            $this->week = intval(date("W"));
        }
    }


    /**
     * définit par quel jour commence le mois
     * @return DateTime
     * @throws Exception
     */
    public function getStartingDay(): DateTime
    {
        return new DateTime("{$this->year}-{$this->month}-01");
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
     * retourne mois a afficher en toutes lettres
     * @return string
     */
    public function fullDate(): string
    {
        return $this->months[$this->month -1] . ' ' . $this->year;
    }

    public function getStartingDayType()
    {
        return $this->getStartingDay()->format('N');
    }

    /**
     * donne le numéro de jour du dernier lundi du mois précédent
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
        $date ->setISOdate($this->year, $this->week);
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
        $date ->setISOdate($this->year, $this->week);
        return $date->modify('-1 month')->format('m/Y/W');
    }
}
