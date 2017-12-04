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
use AppBundle\Utils\PhoneUtils;

class PatientPhoneNumberValidator extends PhoneNumberValidator
{

    public static $path = 'mobilePhone';

    public function __construct(PhoneUtils $phoneUtils)
    {
        parent::__construct($phoneUtils);
    }

    /**
     * @param Patient $patient
     * @return Country|bool
     */
    protected function getCountry($patient)
    {
        if ($patient->getState()) {
            return $patient->getState()->getCountry();
        }
        return null;
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
