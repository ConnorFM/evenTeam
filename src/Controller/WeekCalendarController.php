<?php


namespace App\Controller;

use App\Service\Calendar;
use DateTime;
use Exception;
use Symfony\Component\DependencyInjection\Tests\Compiler\D;

class WeekCalendarController extends AbstractController
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

    /**
     * donne le titre principal de la page en concatenant le jour du début et de la fin de semaine
     * @return string
     * @throws Exception
     */
    public function getTittle()
    {
        $date = new DateTime();
        $date ->setISOdate($this->calendar->year, $this->calendar->week);
        return $date ->format('d F Y') ." to " .$date->modify('+6 day')->format('d F Y');
    }

    /**
     * donne le titre principal de la page en concatenant le jour du début et de la fin de semaine
     * @return string
     * @throws Exception
     */
    public function getMobileTittle()
    {
        $date = new DateTime();
        $date ->setISOdate($this->calendar->year, $this->calendar->week);
        return $date ->format('d m') ." to " .$date->modify('+6 day')->format('d m y');
    }

    /** créer un table comportant l'ensemble des jours
     * @return array de l'ensemble des jour au format jour 00 mois 0000
     * @throws Exception
     */
    public function daysOfWeek()
    {
        $date = new DateTime();
        $date ->setISOdate($this->calendar->year, $this->calendar->week);
        $days = [$date->format('l d F Y')];
        for ($i = 0; $i < 6; $i++) {
            $days[] = $date->modify('+1 days')->format('l d F Y');
        }
        return $days;
    }

    /** créer un table comportant l'ensemble des jours pour vue moins de 991px
     * @return array de l'ensemble des jour au format jour 00 mois 0000
     * @throws Exception
     */
    public function daysOfWeekMobile()
    {
        $date = new DateTime();
        $date ->setISOdate($this->calendar->year, $this->calendar->week);
        $days = [$date->format('D d')];
        for ($i = 0; $i < 6; $i++) {
            $days[] = $date->modify('+1 days')->format('D d');
        }
        return $days;
    }

    /**
     * permet d'accerder a la semaine suivante
     * @return string
     * @throws Exception
     */
    public function nextWeek()
    {
        $date = new DateTime();
        $date ->setISOdate($this->calendar->year, $this->calendar->week);
        return $date->modify('+1 week')->format('m/Y/W');
    }

    /**
     * permet d'acceder à la semaine précédente
     * @return string
     * @throws Exception
     */
    public function previousWeek()
    {
        $date = new DateTime();
        $date ->setISOdate($this->calendar->year, $this->calendar->week);
        return $date->modify('-1 week')->format('m/Y/W');
    }

    /**
     * permet l'affichage du calendrier de la semaine
     * @param null $month
     * @param null $year
     * @param null $week
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show($month = null, $year = null, $week = null)
    {
        $this->setCalendar(new Calendar($month, $year, $week));

        return $this->twig->render('weekCalendar.html.twig', [
                                                                    'fullDate' => $this->getTittle(),
                                                                    'daysOfWeek' => $this->daysOfWeek(),
                                                                    'daysOfWeekMobile' => $this->daysOfWeekMobile(),
                                                                    'next' => $this->nextWeek(),
                                                                    'previous' => $this->previousWeek(),
                                                                    'mobileDate' => $this->getMobileTittle()]);
    }
}
