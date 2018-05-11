<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 14.09.2017
 * Time: 11:32
 */

namespace AppBundle\Validator;

use AppBundle\Entity\ManualCommunication;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ManualCommunicationCorrectValidator extends ConstraintValidator
{

    /**
     * @param ManualCommunication $object
     * @param Constraint $constraint
     */
    public function validate($object, Constraint $constraint)
    {
        if ($object->getCommunicationType()->isBySms() && !$object->getSms()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('sms')->addViolation();
        }
        if ($object->getCommunicationType()->isByEmail() && !$object->getMessage()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('message')->addViolation();
        }
        if ($object->getCommunicationType()->isByEmail() && !$object->getSubject()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('subject')->addViolation();
        }
    }

}
