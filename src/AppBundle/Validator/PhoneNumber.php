<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 14.09.2017
 * Time: 11:30
 */

namespace AppBundle\Validator;

use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as MisdPhoneNumber;

class PhoneNumber extends MisdPhoneNumber {

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}
