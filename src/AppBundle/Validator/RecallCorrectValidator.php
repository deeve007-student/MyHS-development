<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 14.09.2017
 * Time: 11:32
 */

namespace AppBundle\Validator;

use AppBundle\Entity\Phone;
use AppBundle\Entity\Recall;
use AppBundle\Utils\PhoneUtils;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RecallCorrectValidator extends ConstraintValidator
{

    /**
     * @param Recall $object
     * @param Constraint $constraint
     */
    public function validate($object, Constraint $constraint)
    {
        if ($object->getRecallType()->isByCall() && !$object->getNotes()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('notes')->addViolation();
        }
        if ($object->getRecallType()->isBySms() && !$object->getSms()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('sms')->addViolation();
        }
        if ($object->getRecallType()->isByEmail() && !$object->getMessage()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('message')->addViolation();
        }
        if ($object->getRecallType()->isByEmail() && !$object->getSubject()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('subject')->addViolation();
        }
    }

}
