<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 22.03.2017
 * Time: 11:59
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Patient;
use AppBundle\Entity\Phone;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class Formatter
{

    public function formatPhone($phoneCarrierObject)
    {
        $defaultRegion = "AU"; //Todo: remove this hack in future

        $phoneUtils = PhoneNumberUtil::getInstance();

        if ($phoneCarrierObject instanceof Patient) {
            if ($state = $phoneCarrierObject->getState()) {
                $country = $state->getCountry()->getIsoCode();
                $parsedNumber = $phoneUtils->parse($phoneCarrierObject->getMobilePhone(), $country);
            } else {
                $parsedNumber = $phoneUtils->parse($phoneCarrierObject->getMobilePhone(), $defaultRegion);
            }
        } elseif ($phoneCarrierObject instanceof Phone) {
            if ($state = $phoneCarrierObject->getPatient()->getState()) {
                $country = $state->getCountry()->getIsoCode();
                $parsedNumber = $phoneUtils->parse($phoneCarrierObject->getPhoneNumber(), $country);
            } else {
                $parsedNumber = $phoneUtils->parse($phoneCarrierObject->getPhoneNumber(), $defaultRegion);
            }
        } else {
            throw new \Exception('Undefined phone carrier object');
        }

        $intPhone = $phoneUtils->format($parsedNumber, PhoneNumberFormat::INTERNATIONAL);
        $intPhone = preg_replace('/[^\d\+]+/', '', $intPhone);
        return $intPhone;
    }

    public function formatDate(\DateTime $dateTime)
    {
        return $dateTime->format($this->getBackendDateFormat());
    }

    public function formatMomentDate(\DateTime $dateTime)
    {
        return $dateTime->format($this->getMomentDateBackendFormat());
    }

    public function formatDateAndWeekDay(\DateTime $dateTime)
    {
        return $dateTime->format($this->getBackendDateAndWeekDayFormat());
    }

    public function formatDateAndWeekDayFull(\DateTime $dateTime)
    {
        return $dateTime->format($this->getBackendDateAndWeekDayFullFormat());
    }

    public function formatDateTime(\DateTime $dateTime)
    {
        return $dateTime->format($this->getDateTimeBackendFormat());
    }

    public function formatTime(\DateTime $dateTime)
    {
        return $dateTime->format($this->getBackendTimeFormat());
    }

    public function getBackendDateFormat()
    {
        return 'j M Y';
    }

    public function getBackendDateAndWeekDayFormat()
    {
        return 'D, j M Y';
    }

    public function getBackendDateAndWeekDayFullFormat()
    {
        return 'l, j M Y';
    }

    public function getDateTimeBackendFormat()
    {
        return $this->getBackendDateFormat() . ' ' . $this->getBackendTimeFormat();
    }

    public function getMomentDateBackendFormat()
    {
        return 'Y-m-d';
    }

    public function getBackendTimeFormat()
    {
        return 'g:i A';
    }

    public function getBackendHoursFormat()
    {
        return 'g A';
    }

    protected function convertPHPToMomentFormat($format)
    {
        $replacements = [
            'd' => 'DD',
            'D' => 'ddd',
            'j' => 'D',
            'l' => 'dddd',
            'N' => 'E',
            'S' => 'o',
            'w' => 'e',
            'z' => 'DDD',
            'W' => 'W',
            'F' => 'MMMM',
            'm' => 'MM',
            'M' => 'MMM',
            'n' => 'M',
            't' => '', // no equivalent
            'L' => '', // no equivalent
            'o' => 'YYYY',
            'Y' => 'YYYY',
            'y' => 'YY',
            'a' => 'a',
            'A' => 'A',
            'B' => '', // no equivalent
            'g' => 'h',
            'G' => 'H',
            'h' => 'hh',
            'H' => 'HH',
            'i' => 'mm',
            's' => 'ss',
            'u' => 'SSS',
            'e' => 'zz', // deprecated since version 1.6.0 of moment.js
            'I' => '', // no equivalent
            'O' => '', // no equivalent
            'P' => '', // no equivalent
            'T' => '', // no equivalent
            'Z' => '', // no equivalent
            'c' => '', // no equivalent
            'r' => '', // no equivalent
            'U' => 'X',
        ];
        $momentFormat = strtr($format, $replacements);
        return $momentFormat;
    }

}
