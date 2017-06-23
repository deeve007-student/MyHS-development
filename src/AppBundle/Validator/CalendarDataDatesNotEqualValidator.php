<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 13:55
 */

namespace AppBundle\Validator;

use AppBundle\Entity\CalendarData;
use AppBundle\Utils\EventUtils;
use AppBundle\Utils\Formatter;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CalendarDataDatesNotEqualValidator extends ConstraintValidator
{
    /** @var  EventUtils */
    protected $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function validate($event, Constraint $constraint)
    {
        if (!$this->isDatesNotEqual($event)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('workDayEnd')
                ->addViolation();
        }
    }

    protected function isDatesNotEqual(CalendarData $calendarData)
    {
        if (\DateTime::createFromFormat($this->formatter->getBackendTimeFormat(), $calendarData->getWorkDayStart()) == \DateTime::createFromFormat($this->formatter->getBackendTimeFormat(), $calendarData->getWorkDayEnd())) {
            return false;
        }
        return true;
    }
}
