<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 22.03.2017
 * Time: 12:00
 */

namespace AppBundle\Twig;

use AppBundle\Utils\Formatter;

class FormatterExtension extends \Twig_Extension
{

    /** @var Formatter */
    protected $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('app_date', array($this, 'dateFilter')),
            new \Twig_SimpleFilter('app_date_and_week_day', array($this, 'dateAndWeekDayFilter')),
            new \Twig_SimpleFilter('app_date_and_week_day', array($this, 'dateAndWeekDayFilter')),
            new \Twig_SimpleFilter('app_date_and_week_day_full', array($this, 'dateAndWeekDayFilterFull')),
            new \Twig_SimpleFilter('app_time', array($this, 'timeFilter')),
        );
    }

    public function dateFilter($date)
    {
        return $this->formatter->formatDate($date);
    }

    public function dateAndWeekDayFilter($date)
    {
        return $this->formatter->formatDateAndWeekDay($date);
    }

    public function dateAndWeekDayFilterFull($date)
    {
        return $this->formatter->formatDateAndWeekDayFull($date);
    }

    public function timeFilter($date)
    {
        return $this->formatter->formatTime($date);
    }
}

