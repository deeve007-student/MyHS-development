<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 20.06.2017
 * Time: 17:38
 */

namespace AppBundle\Validator;

use AppBundle\Utils\EventUtils;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EventNotOverlapValidator extends ConstraintValidator
{
    /** @var  EventUtils */
    protected $eventUtils;

    public function __construct(EventUtils $eventUtils)
    {
        $this->eventUtils = $eventUtils;
    }

    public function validate($event, Constraint $constraint)
    {
        if ($this->eventUtils->isOverlapping($event)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('start')
                ->addViolation();
        }
    }
}
