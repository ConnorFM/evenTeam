<?php


namespace App\Service;

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
        'Mai',
        'June',
        'Juillet',
        'Août',
        'Septembre',
        'Octobre',
        'Novembre',
        'Décembre'
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
}
