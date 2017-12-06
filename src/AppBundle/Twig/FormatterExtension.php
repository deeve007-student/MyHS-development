<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 22.03.2017
 * Time: 12:00
 */

namespace AppBundle\Twig;

use AppBundle\Utils\Formatter;
use AppBundle\Utils\PhoneUtils;

class FormatterExtension extends \Twig_Extension
{

    /** @var Formatter */
    protected $formatter;

    /** @var PhoneUtils */
    protected $phoneUtils;

    public function __construct(Formatter $formatter, PhoneUtils $phoneUtils)
    {
        $this->formatter = $formatter;
        $this->phoneUtils = $phoneUtils;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('app_date', array($this, 'dateFilter')),
            new \Twig_SimpleFilter('app_date_and_week_day', array($this, 'dateAndWeekDayFilter')),
            new \Twig_SimpleFilter('app_date_and_week_day', array($this, 'dateAndWeekDayFilter')),
            new \Twig_SimpleFilter('app_date_and_week_day_full', array($this, 'dateAndWeekDayFilterFull')),
            new \Twig_SimpleFilter('app_time', array($this, 'timeFilter')),
            new \Twig_SimpleFilter('app_phone_callable', array($this, 'phoneFilterCallable')),
            new \Twig_SimpleFilter('app_phone_pretty', array($this, 'phoneFilterFront')),
            new \Twig_SimpleFilter('app_mobile_phone_valid', array($this, 'phoneMobileValid')),
            new \Twig_SimpleFilter('app_datetime', array($this, 'dateTimeFilter')),
            new \Twig_SimpleFilter('app_date_moment', array($this, 'dateMomentFilter')),
            new \Twig_SimpleFilter('app_time_calendar_widget', array($this, 'calendarWidgetTimeFilter'), [
                'is_safe' => ['html']
            ]),
        );
    }

    public function phoneMobileValid($phone, $country=null)
    {
        return $this->phoneUtils->isValidMobilePhone($phone, $country) ? '<span class="label label-success">valid mobile phone</span>' : '';
    }

    public function phoneFilterCallable($phone, $country=null)
    {
        return $this->formatter->formatPhoneCallable($phone, $country);
    }

    public function phoneFilterFront($phone, $country)
    {
        return $this->formatter->formatPhonePretty($phone, $country);
    }

    public function dateTimeFilter($date)
    {
        return $this->formatter->formatDateTime($date);
    }

    public function dateFilter($date)
    {
        return $this->formatter->formatDate($date);
    }

    public function dateMomentFilter($date)
    {
        return $this->formatter->formatMomentDate($date);
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

    public function calendarWidgetTimeFilter($date)
    {
        return $date->format('g:i').'<span>'.$date->format('A').'</span>';
    }
}

