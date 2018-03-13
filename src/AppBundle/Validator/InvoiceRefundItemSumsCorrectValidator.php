<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 14.09.2017
 * Time: 11:32
 */

namespace AppBundle\Validator;

use AppBundle\Entity\Refund;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\VarDumper\VarDumper;

class InvoiceRefundItemSumsCorrectValidator extends ConstraintValidator
{

    /**
     * @param Constraint $constraint
     * @param Refund $object
     */
    public function validate($object, Constraint $constraint)
    {
        if ($object->getItemsTotal() !== $object->getPaymentsTotal()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('paymentsTotal')->addViolation();
        }
    }

}
