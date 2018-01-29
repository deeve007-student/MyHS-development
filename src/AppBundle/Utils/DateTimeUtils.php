<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 22.08.2017
 * Time: 17:10
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Attachment;
use AppBundle\Entity\Concession;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoicePayment;
use AppBundle\Entity\Patient;
use AppBundle\Entity\PatientAlert;
use AppBundle\Entity\Product;
use AppBundle\Entity\Recall;
use AppBundle\Entity\RecurringTask;
use AppBundle\Entity\Task;
use AppBundle\Entity\Treatment;
use AppBundle\Entity\TreatmentNote;
use AppBundle\Entity\TreatmentNoteField;
use AppBundle\Entity\TreatmentNoteTemplate;
use AppBundle\Entity\UnavailableBlock;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use UserBundle\Entity\User;

class DateTimeUtils
{

    const MONTHES = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December'
    ];

    public function getTimezoneFromOffset($offset)
    {
        list($hours, $minutes) = explode(':', $offset);
        $seconds = $hours * 60 * 60 + $minutes * 60;
        $tz = timezone_name_from_abbr('', $seconds, 1);
        if ($tz === false) {
            $tz = timezone_name_from_abbr('', $seconds, 0);
        }
        return $tz;
    }

    /**
     * @return \DateTime
     */
    public static function getNowDateTime()
    {
        return new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * @return \DateTime
     */
    public static function getNowDate()
    {
        return self::getNowDateTime()->modify('midnight');
    }

    /**
     * @return \DateTimeZone
     */
    public static function getTimezone()
    {
        return new \DateTimeZone('Europe/Moscow');
    }

    /**
     * @return \DateTime
     */
    public static function getDate(\DateTimeInterface $dateTime)
    {
        /** @var \DateTime $dt */
        $dt = clone $dateTime;
        $dt = $dt->modify('midnight');
        return $dt;
    }

    /**
     * @param \DateTime $dateTime
     * @return string
     */
    public static function formatDate(\DateTime $dateTime)
    {
        return $dateTime->format('d.m.Y');
    }

    /**
     * @param \DateTime $dateTime
     * @return string
     */
    public static function formatMonth(\DateTime $dateTime)
    {
        return self::MONTHES[$dateTime->format('n')];
    }

    /**
     * @param \DateTime $dateTime
     * @return string
     */
    public static function formatYear(\DateTime $dateTime)
    {
        return $dateTime->format('Y');
    }

    /**
     * @param \DateTime $dateTime
     * @return string
     */
    public static function formatYearMonth(\DateTime $dateTime)
    {
        return self::formatMonth($dateTime).' '.self::formatYear($dateTime);
    }

    /**
     * @param \DateTime $dateTime
     * @return string
     */
    public static function formatDateTime(\DateTime $dateTime)
    {
        return $dateTime->format('d.m.Y H:i:s');
    }

    public static function datesEquals(\DateTimeInterface $dateTimeA, \DateTimeInterface $dateTimeB)
    {
        return self::getDate($dateTimeA)->format('d.m.Y') == self::getDate($dateTimeB)->format('d.m.Y') ? true : false;
    }


    /**
     * @return \DateTime
     */
    public static function getCurrentMonth()
    {
        return self::getMonthByDate(self::getNowDate());
    }

    /**
     * @return \DateTime
     */
    public static function getMonthByDate(\DateTime $dateTime)
    {
        $dt = clone $dateTime;
        $dt->setTimezone(new \DateTimeZone('UTC'));

        return self::getDate($dt->modify('first day of this month'));
    }

    /**
     * @return \DateTime
     */
    public static function getMonthWithConsideringFirstDays($firstDay = 1, $lastDay = 5)
    {
        $currentMonth = self::getCurrentMonth();

        if (self::getNowDate()->format('j') >= $firstDay && self::getNowDate()->format('j') <= $lastDay) {
            return self::getMonthByDate($currentMonth->modify("-1 month"));
        } else {
            return $currentMonth;
        }
    }

    public static function getMonthByDateMidnight(\DateTime $dateTime) {
        return self::getMonthByDate($dateTime)->modify('midnight');
    }

    public static function compareMonthes(\DateTime $dateA, $dateB) {
        return $dateA->format('m') == $dateB->format('m') && $dateA->format('Y') == $dateB->format('Y');
    }

    public static function createDateFromString($date, $format = 'd-m-Y') {
        return \DateTime::createFromFormat($format, $date);
    }

    public static function lessThan(\DateTime $dateA, \DateTime $dateB) {
        return self::getMonthByDateMidnight($dateA) < self::getMonthByDateMidnight($dateB);
    }

    public static function equal(\DateTime $dateA, \DateTime $dateB) {
        return self::getMonthByDateMidnight($dateA) === self::getMonthByDateMidnight($dateB);
    }

    public static function getLastDateInMonth(\DateTime $date) {
        $lastDate = date("Y-m-t", strtotime($date->format("Y-m-d")));
        $lastDate = self::createDateFromString($lastDate, "Y-m-d");

        return $lastDate;
    }
}
