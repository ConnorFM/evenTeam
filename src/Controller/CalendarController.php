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
    protected $postData;
    protected $action;

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
    public function getPostData()
    {
        return $this->postData;
    }

    /**
     * @param mixed $postData
     */
    public function setPostData($postData): void
    {
        $this->postData = $postData;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action): void
    {
        $this->action = ucfirst($action);
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

        $users = $this->userManager->selectAll();
        $usersjson = json_encode($users);
        $rooms = $this->roomManager->selectAll();
        $roomsjson = json_encode($rooms);

        if (isset($mode)) {
            $next = $this->getCalendar()->nextMonth();
            $next .=  "/$mode/$id";
            $previous = $this->getCalendar()->previousMonth();
            $previous .=  "/$mode/$id";
        } else {
            $next = $this->getCalendar()->nextMonth();
            $previous = $this->getCalendar()->previousMonth();
        }

        return $this->twig->render('monthCalendar.html.twig', [
                                                                'fullDate' => $this->getCalendar()->fullDate(),
                                                                'next' => $next,
                                                                'previous' => $previous,
                                                                'calendar' => $this->getCalendar()->generateMonth(),
                                                                'days' => $this->getCalendar()->days,
                                                                'rooms' => $this->roomManager->selectAll(),
                                                                'users' => $this->userManager->selectAll(),
                                                                'events' => $this->events($mode, $id),
                                                                'roomsjson' => $roomsjson,
                                                                'usersjson' => $usersjson,
                                                                'message'   => $this->getMessages(),
                                                                'postData' => $this->getPostData(),
                                                                'mode' => $mode,
                                                                'userOrRoomId' => $id,
                                                                'allEvents' => $this->userManager->getAllUsersEvents(),
                                                                'action' => $this->getAction()
                                                                ]);
    }


    public function week($month = null, $year = null, $week = null, $mode = null, $id = null)
    {
        $this->setCalendar(new Calendar($month, $year, $week));

        $users = $this->userManager->selectAll();
        $usersjson = json_encode($users);

        if (isset($mode)) {
            $next = $this->getCalendar()->nextWeek();
            $next .=  "/$mode/$id";
            $previous = $this->getCalendar()->previousWeek();
            $previous .=  "/$mode/$id";
        } else {
            $next = $this->getCalendar()->nextWeek();
            $previous = $this->getCalendar()->previousWeek();
        }

        return $this->twig->render('weekCalendar.html.twig', [
                                                                    'fullDate' => $this->getCalendar()->getTitle(),
                                                                    'daysOfWeek' => $this->getCalendar()->daysOfWeek(),
                                                                    'next' => $next,
                                                                    'previous' => $previous,
                                                                    'calendar' => $this->getCalendar()->generateWeek(),
                                                                    'rooms' => $this->roomManager->selectAll(),
                                                                    'users' => $this->userManager->selectAll(),
                                                                    'events' => $this->events($mode, $id),
                                                                    'usersjson' => $usersjson,
                                                                    'message'   => $this->getMessages(),
                                                                    'postData' => $this->getPostData(),
                                                                    'mode' => $mode,
                                                                    'userOrRoomId' => $id
                                                                    ]);
    }
}
