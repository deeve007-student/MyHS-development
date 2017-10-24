<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 07.06.2017
 * Time: 12:45
 */

namespace ReportBundle\Form\Type;

use AppBundle\Utils\DateRangeUtils;
use AppBundle\Utils\DateTimeUtils;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateRangeType extends AbstractType
{

    const CHOICE_ALL = 'all';
    const CHOICE_QUARTER = 'quarter';
    const CHOICE_PREV_QUARTER = 'prevQuarter';
    const CHOICE_NEXT_QUARTER = 'nextQuarter';
    const CHOICE_MONTH = 'month';
    const CHOICE_PREV_MONTH = 'prevMonth';
    const CHOICE_PREV_3_MONTHES = 'prev3monthes';
    const CHOICE_YEAR = 'year';
    const CHOICE_PREV_YEAR = 'prevYear';
    const CHOICE_FIN_YEAR = 'finYear';
    const CHOICE_PREV_FIN_YEAR = 'prevFinYear';
    const RANGE = 'range';

    public static function getRangeDates($value)
    {
        $start = null;
        $end = null;

        switch ($value) {
            case 'all':
                list($start, $end) = DateRangeUtils::getEternityDates();
                break;
            case 'quarter':
                list($start, $end) = DateRangeUtils::getQuarterDates(new \DateTime());
                break;
            case 'prevQuarter':
                list($start, $end) = DateRangeUtils::getQuarterDates(null, -1);
                break;
            case 'nextQuarter':
                list($start, $end) = DateRangeUtils::getQuarterDates(null, 1);
                break;
            case 'month':
                list($start, $end) = DateRangeUtils::getMonthDates();
                break;
            case 'prevMonth':
                $dt = new \DateTime('now', DateTimeUtils::getTimezone());
                list($start, $end) = DateRangeUtils::getMonthDates($dt->modify('last month'));
                break;
            case 'prev3monthes':
                $dt = new \DateTime('now', DateTimeUtils::getTimezone());
                $start = DateRangeUtils::getMonthDates()[0]->modify('last month')->modify('last month');
                $end = DateRangeUtils::getMonthDates()[1];
                break;
            case 'year':
                list($start, $end) = DateRangeUtils::getYearDates();
                break;
            case 'prevYear':
                $dt = new \DateTime('now', DateTimeUtils::getTimezone());
                list($start, $end) = DateRangeUtils::getYearDates($dt->modify('last year'));
                break;
            case 'finYear':
                list($start, $end) = DateRangeUtils::getFinancialYearDates();
                break;
            case 'prevFinYear':
                $dt = new \DateTime('now', DateTimeUtils::getTimezone());
                list($start, $end) = DateRangeUtils::getFinancialYearDates($dt->modify('last year'));
                break;
        }

        return array($start, $end);
    }

    protected function formatDatesRanges(array $dates)
    {
        $dateStart = DateTimeUtils::MONTHES[$dates[0]->format('n')] . ' ' . $dates[0]->format('Y');
        $dateEnd = DateTimeUtils::MONTHES[$dates[1]->format('n')] . ' ' . $dates[1]->format('Y');
        return $dateStart !== $dateEnd ? $dateStart . ' - ' . $dateEnd : $dateStart;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'label' => 'app.report.date_range',
                'ranges' => array(),
                'available_choices' => array(
                    self::CHOICE_ALL => 'All',
                    self::CHOICE_QUARTER => 'Current quarter(' . $this->formatDatesRanges(self::getRangeDates(self::CHOICE_QUARTER)) . ')',
                    self::CHOICE_PREV_QUARTER => 'Prev quarter (' . $this->formatDatesRanges(self::getRangeDates(self::CHOICE_PREV_QUARTER)) . ')',
                    self::CHOICE_NEXT_QUARTER => 'Next quarter (' . $this->formatDatesRanges(self::getRangeDates(self::CHOICE_NEXT_QUARTER)) . ')',
                    self::CHOICE_MONTH => 'Current month (' . $this->formatDatesRanges(self::getRangeDates(self::CHOICE_MONTH)) . ')',
                    self::CHOICE_PREV_MONTH => 'Prev month (' . $this->formatDatesRanges(self::getRangeDates(self::CHOICE_PREV_MONTH)) . ')',
                    self::CHOICE_PREV_3_MONTHES => 'Prev 3 monthes (' . $this->formatDatesRanges(self::getRangeDates(self::CHOICE_PREV_3_MONTHES)) . ')',
                    self::CHOICE_YEAR => 'Current year (' . $this->formatDatesRanges(self::getRangeDates(self::CHOICE_YEAR)) . ')',
                    self::CHOICE_PREV_YEAR => 'Prev year (' . $this->formatDatesRanges(self::getRangeDates(self::CHOICE_PREV_YEAR)) . ')',
                    self::CHOICE_FIN_YEAR => 'Current financial year (' . $this->formatDatesRanges(self::getRangeDates(self::CHOICE_FIN_YEAR)) . ')',
                    self::CHOICE_PREV_FIN_YEAR => 'Prev financial year (' . $this->formatDatesRanges(self::getRangeDates(self::CHOICE_PREV_FIN_YEAR)) . ')',
                    self::RANGE => 'Choose range',
                ),
            ]
        );

        $resolver->setDefault('choices', function (Options $options) {
            $choices = array();

            if (count($options['ranges'])) {
                foreach ($options['ranges'] as $range) {
                    if (array_key_exists($range, $options['available_choices'])) {
                        $choices[$range] = $options['available_choices'][$range];
                    }
                }
            } else {
                $choices = $options['available_choices'];
            }

            return $choices;
        });
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_date_range';
    }
}
