<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 14.09.2017
 * Time: 11:30
 */

namespace AppBundle\Validator;

use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as MisdPhoneNumber;
use Symfony\Component\Validator\Constraint;

class PhoneNumber extends Constraint {

    public $message = 'Wrong number';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}
