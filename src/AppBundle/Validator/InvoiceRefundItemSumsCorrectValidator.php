<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 14.09.2017
 * Time: 11:32
 */

namespace AppBundle\Validator;

use AppBundle\Entity\Refund;
use AppBundle\Entity\TreatmentPackCredit;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\VarDumper\VarDumper;

class InvoiceRefundItemSumsCorrectValidator extends ConstraintValidator
{

    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Constraint $constraint
     * @param Refund $object
     */
    public function validate($object, Constraint $constraint)
    {
        if ($object->getPaymentsTotal() > $object->getInvoice()->getPossibleMaximumRefundAmount()) {
            $this->context->buildViolation($this->translator->trans($constraint->message))
                ->atPath('paymentsTotal')->addViolation();
        }
    }

}