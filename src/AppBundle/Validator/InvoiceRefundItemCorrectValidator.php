<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 14.09.2017
 * Time: 11:32
 */

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class InvoiceRefundItemCorrectValidator extends ConstraintValidator
{

    /**
     * @param Constraint $constraint
     * @param array $object
     */
    public function validate($object, Constraint $constraint)
    {

        if ($object['amount'] > $object['paid']) {
            $this->context->buildViolation($constraint->message)
                ->atPath('[amount]')->addViolation();
        }
    }

}
