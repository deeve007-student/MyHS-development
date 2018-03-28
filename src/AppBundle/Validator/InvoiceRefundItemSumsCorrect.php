<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 05.03.18
 * Time: 18:38
 */

namespace AppBundle\Validator;

use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as MisdPhoneNumber;
use Symfony\Component\Validator\Constraint;

class InvoiceRefundItemSumsCorrect extends Constraint
{

    public $message = 'This value should less than payments sum with previous made refunds';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}