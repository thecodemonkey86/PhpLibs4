<?php
namespace PhpLibs\Util;

use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;


class DateUtil {
     public static function parseDate($input, $timezone = null) {
        $year = null;
        $month = null;
        $day = null;
        if (substr_count($input, ".") == 2) {
            $parts = explode(".", $input);
            $day = $parts[0];
            $month = $parts[1];
            if (strlen($parts[2]) == 2) {
                if ((int) date("Y") < $parts[2] + 2000) {
                    $year = $parts[2] + 1900;
                } else {
                    $year = $parts[2] + 2000;
                }
            } else {
                $year = $parts[2];
            }
        } else {
            $parts = null;
            if (substr_count($input, "-") == 2) {
                $parts = explode("-", $input);
            } else if (substr_count($input, "/") == 2) {
                $parts = explode("/", $input);
            }

            $day = $parts[2];
            $month = $parts[1];
            if (strlen($parts[0]) == 2) {
                if ((int) date("Y") < $parts[0] + 2000) {
                    $year = $parts[0] + 1900;
                } else {
                    $year = $parts[0] + 2000;
                }
            } else {
                $year = $parts[0];
            }
        }
        if (strlen($year) > 0 && strlen($month) > 0 && strlen($day) > 0) {
            return new DateTime($year . "-" . $month . "-" . $day, ($timezone=== null ? new DateTimeZone('UTC') : $timezone ));
        } else {
            throw new Exception("Ungültige Eingabe: " . var_export($input, true));
        }
    }

   
    public static function diffDays(DateTime $d1, DateTime $d2) {
        $interval = $d1->diff($d2, false);
        return $interval->invert == 1 ? - $interval->days : $interval->days;
    }

    public static function diffMonths(DateTime $d1, DateTime $d2) {
        $interval = $d1->diff($d2, false);
        return $interval->invert == 1 ? - $interval->m : $interval->m;
    }

    public static function diffYears(DateTime $d1, DateTime $d2) {
        $interval = $d1->diff($d2, false);
        return $interval->invert == 1 ? - $interval->y : $interval->y;
    }
    
    public static function getMonthNamesDeutsch() {
        return array(
            'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'
        );
    }

    public static function monthNameDeutsch(DateTime $dt) {
        $names = getMonthNamesDeutsch();
        return $names[(int) ($dt->format('n')) - 1];
    }

    public static function monthShortNameDeutsch(DateTime $dt) {
        $names = array(
            'Jan', 'Feb', 'MÃ¤r', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'
        );
        return $names[(int) ($dt->format('n')) - 1];
    }

    public static function monthMediumNameDeutsch(DateTime $dt) {
        $names = array(
            'Jan', 'Feb', 'März', 'April', 'Mai', 'Jun', 'Jul', 'Aug', 'Sept', 'Okt', 'Nov', 'Dez'
        );
        return $names[(int) ($dt->format('n')) - 1];
    }

    public static function weekdayShortNameDeutsch(DateTime $dt) {
        $names = array(
            'So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'
        );
        return $names[(int) ($dt->format('w'))];
    }

    public static function weekdayDeutsch(DateTime $dt) {
        $names = array(
            'Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'
        );
        return $names[(int) ($dt->format('w'))];
    }

    public static function getWeekdaysDeutsch() {
        return array(
            'Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'
        );
    }

    public static function addDays(DateTime $dt, $days) {
        $copy = clone $dt;
        return $copy->add(new DateInterval("P" . $days . "D"));
    }
    
    public static function subtractDays(DateTime $dt, $days) {
        $copy = clone $dt;
        return $copy->sub(new DateInterval("P" . $days . "D"));
    }
    
    
    public static function addDay(DateTime $dt) {
        $copy = clone $dt;
        return $copy->add(new DateInterval("P1D"));
    }
    
    public static function addMonth(DateTime $dt) {
        $copy = clone $dt;
        return $copy->add(new DateInterval("P1M"));
    }
    
    public static function addWeeks(DateTime $dt, $weeks = 1) {
        $copy = clone $dt;
        return $copy->add(new DateInterval("P" . $weeks . "W"));
    }
    
    public static function addWeek(DateTime $dt) {
        $copy = clone $dt;
        return $copy->add(new DateInterval("P7D"));
    }
    
    public static function addMonths(DateTime $dt, $months = 1) {
        if($months > 0) {
            $copy = clone $dt;
            return $copy->add(new DateInterval("P" . $months . "M"));
        } else if($months < 0){
            $copy = clone $dt;
            return $copy->sub(new DateInterval("P" . (- $months) . "M"));
        } else {
            return clone $dt;
        }
        
    }
    
    public static function getTodayMidnight() {
        $dt = new DateTime();
        $dt->setTime(0, 0, 0);
        return $dt;
    }
    
    public static function removeTime(DateTime $dateTime) { 
        $copy = clone $dateTime;
        $copy->setTime(0, 0, 0);
        return $copy;
    }
}
