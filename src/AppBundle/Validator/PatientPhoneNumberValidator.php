<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 14.09.2017
 * Time: 11:32
 */

namespace AppBundle\Validator;

use AppBundle\Entity\Country;
use AppBundle\Entity\Patient;

class PatientPhoneNumberValidator extends PhoneNumberValidator
{

    public static $path = 'mobilePhone';

    /**
     * @param Patient $patient
     * @return Country|bool
     */
    protected function getCountry($patient)
    {
        if ($patient->getState()) {
            return $patient->getState()->getCountry();
        }
        return false;
    }

    /**
     * @param Patient $patient
     * @return string
     */
    protected function getPhoneNumber($patient)
    {
        return $patient->getMobilePhone();
    }

}
