<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 17.02.2017
 * Time: 15:37
 */

namespace AppBundle\Utils;

/**
 * Class DateRangeUtils
 */
class DateRangeUtils
{

    /**
     * Возвращает дату и время начала и конца предыдущего квартала (текущего или на переданную дату)
     * Пример 2017-01-01 00:00:00 и 2017-03-31 23:59:59
     *
     * @param \DateTime|null $dateTime
     * @return array|\DateTime[]
     */
    public static function prevQuarterDates($dateTime)
    {
        $dateTime = clone $dateTime;

        list($dateTime, $quarterEnd) = self::getQuarterDates($dateTime);

        for ($n = 1; $n <= 3; $n++) {
            $dateTime = $dateTime->modify('first day of last month');
            $quarterEnd = $quarterEnd->modify('last day of this month');
        }
        return array($dateTime, $quarterEnd->modify('-1 second'));
    }

    /**
     * Возвращает дату и время начала и конца месяца (текущего или на переданную дату)
     * Пример 2017-06-01 00:00:00 и 2017-06-30 23:59:59
     *
     * @param \DateTime|null $dateTime
     * @return array|\DateTime[]
     */
    public static function getMonthDates(\DateTime $dateTime = null)
    {
        if (!$dateTime) {
            $dateTime = new \DateTime('now', DateTimeUtils::getTimezone());
        }
        $dateTime = clone $dateTime;
        $dateTime = DateTimeUtils::getDate($dateTime);

        $start = clone $dateTime;
        $end = clone $dateTime;

        return array(
            $start->modify('first day of this month'),
            $end->modify('first day of next month')->modify('-1 second'),
        );
    }

    /**
     * Возвращает дату и время начала и конца года (текущего или на переданную дату)
     * Пример 2017-01-01 00:00:00 и 2017-12-31 23:59:59
     *
     * @param \DateTime|null $dateTime
     * @return array|\DateTime[]
     */
    public static function getYearDates(\DateTime $dateTime = null)
    {
        if (!$dateTime) {

            $dateTime = new \DateTime('now', DateTimeUtils::getTimezone());
        }
        $dateTime = DateTimeUtils::getDate(clone $dateTime);

        $start = clone $dateTime;
        $start = $start->modify('first day of january');

        $end = clone $dateTime;
        $end = $end->modify('first day of january');
        $end = $end->modify('next year');
        $end = $end->modify('-1 second');

        return array(
            $start,
            $end,
        );
    }

    /**
     * Возвращает дату и время начала и конца всех времен
     */
    public static function getEternityDates()
    {
        return array(
            new \DateTime('0000-01-01'),
            new \DateTime('9999-12-31'),
        );
    }

    /**
     * Возвращает дату и время начала и конца финансового года (текущего или на переданную дату)
     * Пример 2017-01-01 00:00:00 и 2017-12-31 23:59:59
     *
     * @param \DateTime|null $dateTime
     * @return array|\DateTime[]
     */
    public static function getFinancialYearDates(\DateTime $dateTime = null)
    {
        if (!$dateTime) {
            $dateTime = new \DateTime('now', DateTimeUtils::getTimezone());
        }
        $dateTime = DateTimeUtils::getDate(clone $dateTime);

        if ((int)$dateTime->format('n') < 4) {
            $dateTime = $dateTime->modify('last year');
        }

        $start = clone $dateTime;
        $start = $start->modify('first day of april');

        $end = clone $dateTime;
        $end = $end->modify('first day of april');
        $end = $end->modify('next year');
        $end = $end->modify('-1 second');

        return array(
            $start,
            $end,
        );
    }

    /**
     * Возвращает дату начала и конца текущего квартала (текущего или на переданную дату)
     * Пример 2017-01-01 00:00:00 и 2017-03-31 23:59:59
     *
     * Возможо сдвигать квартал вперед или назад через параметр $quarterOffset
     * Пример +1 вернет следующий квартал, а -2 - позапрошлый
     *
     * @param \DateTimeInterface $dateTime
     * @param int $quarterOffset
     * @return array
     */
    public static function getQuarterDates(\DateTimeInterface $dateTime = null, $quarterOffset = 0)
    {
        if (!$dateTime) {
            $dateTime = new \DateTime();
        }

        if ($quarterOffset !== 0) {
            for ($n = 1; $n <= abs($quarterOffset); $n++) {
                $ofsetString = $quarterOffset < 0 ? 'first day of last month' : 'first day of next month';
                $dateTime = $dateTime->modify($ofsetString)->modify($ofsetString)->modify($ofsetString);
            }
        }

        $quarterNumber = ceil($dateTime->format('m') / 3);
        $quarterStartMonth = ($quarterNumber - 1) * 3 + 1;

        $quarterStartDate = DateTimeUtils::getDate(\DateTime::createFromFormat('Y-m-d', $dateTime->format('Y') . '-' . $quarterStartMonth . '-01', new \DateTimeZone('UTC')));
        $quarterEndDate = clone $quarterStartDate;
        $quarterEndDate = DateTimeUtils::getDate($quarterEndDate->modify('+2 month')->modify('first day of next month'));
        $quarterEndDate = $quarterEndDate->modify('-1 second');

        return array(
            $quarterStartDate,
            $quarterEndDate
        );
    }

    public static function getFinQuartersDates(\DateTimeInterface $dateTime = null) {
        if (!$dateTime) {
            $dateTime = new \DateTime();
        }

        $result = [];

        $finYearsDates = self::getFinancialYearDates($dateTime);

        $quarterNumber = ceil($finYearsDates[0]->format('m') / 3);
        $quarterStartMonth = ($quarterNumber - 1) * 3 + 1;

        $quarterStartDate = DateTimeUtils::getDate(\DateTime::createFromFormat('Y-m-d', $dateTime->format('Y') . '-' . $quarterStartMonth . '-01', new \DateTimeZone('UTC')));
        $quarterEndDate = clone $quarterStartDate;
        $quarterEndDate = DateTimeUtils::getDate($quarterEndDate->modify('+2 month')->modify('first day of next month'));
        $quarterEndDate = $quarterEndDate->modify('-1 second');

        $result = [
            [$quarterStartDate, $quarterEndDate]
        ];

        for ($i = 0; $i < 3; $i++) {
            $nextQuarter = clone $result[$i][0];
            $result[] = self::getQuarterDates($nextQuarter, 1);
        }

        return $result;
    }

}
