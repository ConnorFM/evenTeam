<?php


namespace App\Service;

use DateTime;
use Exception;
use Symfony\Component\DependencyInjection\Tests\Compiler\D;
use Twig\Environment;

class Calendar
{
    public $days = [
        0 => 'Monday',
        1 => 'Tuesday',
        2 => 'Wednesday',
        3 => 'Thursday',
        4 => 'Friday',
        5 => 'Saturday',
        6 => 'Sunday'
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
     * Définit par quel jour commence le mois.
     * @return DateTime
     * @throws Exception
     */
    public function getStartingDay(): DateTime
    {
        return new DateTime("{$this->year}-{$this->month}-01");
    }

    /**
     * Donne le nombre de semaine a afficher.
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
     * Retourne mois a afficher en toutes lettres
     * @return string
     */
    public function fullDate(): string
    {
        return $this->months[$this->month -1] . ' ' . $this->year;
    }

    /**
     * Retourne le jour de la semaine de début de mois
     * @return string
     * @throws Exception
     */
    public function getStartingDayType()
    {
        return $this->getStartingDay()->format('N');
    }

    /**
     * Donne le numéro de jour du dernier lundi du mois précédent
     * @return DateTime
     * @throws Exception
     */
    public function getFirstMonday()
    {
        return $this->getStartingDay()->modify('last monday');
    }


    /**
     * Renvoie au mois suivant
     * @return string next month well formated
     * @throws Exception
     */
    public function nextMonth()
    {
        $date = $this->getStartingDay();
        return $date->modify('+1 month')->format('m/Y/W');
    }

    /**
     * Renvoie au mois précédent
     * @return string previous mounth well formated
     * @throws Exception
     */
    public function previousMonth()
    {
        $date = $this->getStartingDay();
        return $date->modify('-1 month')->format('m/Y/W');
    }


    /**
     * Permet d'acceder a la semaine suivante
     * @return string
     * @throws Exception
     */
    public function nextWeek()
    {
        $date = new DateTime();
        $date ->setISOdate($this->year, $this->week);
        return $date->modify('+1 week')->format('m/Y/W');
    }

    /**
     * Permet d'acceder à la semaine précédente
     * @return string
     * @throws Exception
     */
    public function previousWeek()
    {
        $date = new DateTime();
        $date ->setISOdate($this->year, $this->week);
        return $date->modify('-1 week')->format('m/Y/W');
    }


    /**
     * Donne le titre principal de la page en concatenant le jour du début et de la fin de semaine
     * @return string
     * @throws Exception
     */
    public function getTitle()
    {
        $date = new DateTime();
        $date ->setISOdate($this->year, $this->week);
        return $date ->format('d F Y') ." to " .$date->modify('+6 day')->format('d F Y');
    }

    /**
     * Donne le titre principal de la page en concatenant le jour du début et de la fin de semaine
     * @return string
     * @throws Exception
     */
    public function getMobileTitle()
    {
        $date = new DateTime();
        $date ->setISOdate($this->year, $this->week);
        return $date ->format('d.m') ." to " .$date->modify('+6 day')->format('d.m.y');
    }

    /** Créer un table comportant l'ensemble des jours
     * @return array de l'ensemble des jour au format jour 00 mois 0000
     * @throws Exception
     */
    public function daysOfWeek()
    {
        $date = new DateTime();
        $date ->setISOdate($this->year, $this->week);
        $days = [];
        for ($i = 0; $i <= 6; $i++) {
            $days[] = (clone $date)->modify('+' . $i .' days')->format('l d F Y H:i');
        }
        return $days;
    }




    /**
   * Jour dans le mois en cours?
   * @param  \DateTime $date [description]
   * @return bool
   * @throws Exception
   */
    public function withinMonth(DateTime $date): bool
    {
        return $this->getStartingDay()->format('Y-m') === $date->format('Y-m');
    }

    /**
     * Crée un tableau associatif par mois qui définit le nombre de jours à afficher et les affiche
     * [generateMonth description]
     * @return array
     * @throws Exception
     */
    public function generateMonth()
    {
        $nbDays = ($this->getWeeks()+1) * 7;
        $monthArray=[];
        $firstDayType = $this->getStartingDayType();
        if ($firstDayType == 1) {
            $firstDay = $this->getStartingDay();
        } else {
            $firstDay = $this->getFirstMonday();
        }

        for ($i = 0; $i < $nbDays; $i++) {
            $monthArray[] = [
                (clone $firstDay)->modify('+' . $i . 'day'),
                $this->withinMonth((clone $firstDay)->modify('+' . $i . 'day'))];
        }

        return $monthArray;
    }



    public function generateDay($j)
    {
        $date = new DateTime();
        $date ->setISOdate($this->year, $this->week)->setTime($j, 00);
        $hours = [];
        for ($i = 0; $i <7; $i++) {
            $hours[] = (clone $date)->modify('+' . $i .'day');
        }
        return $hours;
    }

    public function generateWeek()
    {
        $array=[];
        for ($i=0; $i<24; $i+=2) {
            array_push($array, $this->generateDay($i));
        }
        return $array;
    }
}
