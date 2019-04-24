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
use App\Model\UserManager;
use Twig\Environment;
use App\Service\Calendar;
use App\Model\RoomManager;

/**
 * Class ItemController
 *
 */
class CalendarController extends AbstractController
{
    private $calendar;
    private $roomManager;
    private $userManager;
    private $eventManager;

    public function __construct($month = null, $year = null)
    {
        parent::__construct();
        $this->setCalendar(new Calendar($month, $year));
        $this->roomManager = new RoomManager();
        $this->userManager = new UserManager();
        $this->eventManager = new EventManager();
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


    public function month($month = null, $year = null, $week = null, $mode = null, $id = null)
    {
        $this->setCalendar(new Calendar($month, $year, $week));

        $events = [];
        if (isset($mode)) {
            if ($mode === 'room') {
                $events = $this->eventManager->getRoomsEvents($id);
            } elseif ($mode === 'user') {
                $events = $this->eventManager->getUserEvents($id);
            }
        } else {
            $events = $this->eventManager->getUserEvents($_SESSION['user_id']);
        }


        return $this->twig->render('monthCalendar.html.twig', [
                                                                'fullDate' => $this->getCalendar()->fullDate(),
                                                                'next' => $this->getCalendar()->nextMonth(),
                                                                'previous' => $this->getCalendar()->previousMonth(),
                                                                'calendar' => $this->getCalendar()->generateMonth(),
                                                                'days' => $this->getCalendar()->days,
                                                                'rooms' => $this->roomManager->selectAll(),
                                                                'users' => $this->userManager->selectAll(),
                                                                'events' => $events
                                                                ]);
    }


    public function week($month = null, $year = null, $week = null, $mode = null, $id = null)
    {
        $this->setCalendar(new Calendar($month, $year, $week));

        $events = [];

        if (isset($mode)) {
            if ($mode === 'room') {
                $events = $this->eventManager->getRoomsEvents($id);
            } elseif ($mode === 'user') {
                $events = $this->eventManager->getUserEvents($id);
            }
        } else {
            $events = $this->eventManager->getUserEvents((int)$_SESSION['id']);
        }

        return $this->twig->render('weekCalendar.html.twig', [
                                                                    'fullDate' => $this->getCalendar()->getTitle(),
                                                                    'daysOfWeek' => $this->getCalendar()->daysOfWeek(),
                                                                    'next' => $this->getCalendar()->nextWeek(),
                                                                    'previous' => $this->getCalendar()->previousWeek(),
                                                                    'calendar' => $this->getCalendar()->generateWeek(),
                                                                    'rooms' => $this->roomManager->selectAll(),
                                                                    'users' => $this->userManager->selectAll(),
                                                                    'events' => $events
                                                                    ]);
    }
}
