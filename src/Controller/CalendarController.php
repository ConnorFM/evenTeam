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
    protected $calendar;
    protected $roomManager;
    protected $userManager;
    protected $eventManager;
    protected $messages;
    protected $postDatas;

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
    protected function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * @param mixed $calendar
     */
    protected function setCalendar($calendar): void
    {
        $this->calendar = $calendar;
    }

    public function setMessages($messages)
    {
        $this->messages = $messages;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return mixed
     */
    public function getPostDatas()
    {
        return $this->postDatas;
    }

    /**
     * @param mixed $postDatas
     */
    public function setPostDatas($postDatas): void
    {
        $this->postDatas = $postDatas;
    }


    public function events($mode, $id)
    {
        if (isset($mode)) {
            if ($mode === 'room') {
                return $this->eventManager->getRoomsEvents($id);
            } elseif ($mode === 'user') {
                return $this->eventManager->getUserEvents($id);
            }
        } else {
            return $this->eventManager->getUserEvents($_SESSION['id']);
        }
    }


    public function month($month = null, $year = null, $week = null, $mode = null, $id = null)
    {
        $this->setCalendar(new Calendar($month, $year, $week));
        $userManager = new UserManager();
        $users = $userManager->selectAll();
        $usersjson = json_encode($users);

        return $this->twig->render('monthCalendar.html.twig', [
                                                                'fullDate' => $this->getCalendar()->fullDate(),
                                                                'next' => $this->getCalendar()->nextMonth(),
                                                                'previous' => $this->getCalendar()->previousMonth(),
                                                                'calendar' => $this->getCalendar()->generateMonth(),
                                                                'days' => $this->getCalendar()->days,
                                                                'rooms' => $this->roomManager->selectAll(),
                                                                'users' => $this->userManager->selectAll(),
                                                                'events' => $this->events($mode, $id),
                                                                'usersjson' => $usersjson,
                                                                'message'   => $this->getMessages(),
                                                                'postDatas' => $this->getPostDatas()
                                                                ]);
    }


    public function week($month = null, $year = null, $week = null, $mode = null, $id = null)
    {
        $this->setCalendar(new Calendar($month, $year, $week));
        $userManager = new UserManager();
        $users = $userManager->selectAll();
        $usersjson = json_encode($users);
        return $this->twig->render('weekCalendar.html.twig', [
                                                                    'fullDate' => $this->getCalendar()->getTitle(),
                                                                    'daysOfWeek' => $this->getCalendar()->daysOfWeek(),
                                                                    'next' => $this->getCalendar()->nextWeek(),
                                                                    'previous' => $this->getCalendar()->previousWeek(),
                                                                    'calendar' => $this->getCalendar()->generateWeek(),
                                                                    'rooms' => $this->roomManager->selectAll(),
                                                                    'users' => $this->userManager->selectAll(),
                                                                    'events' => $this->events($mode, $id),
                                                                    'usersjson' => $usersjson,
                                                                    'message'   => $this->getMessages(),
                                                                    'postDatas' => $this->getPostDatas()
                                                                    ]);
    }
}
