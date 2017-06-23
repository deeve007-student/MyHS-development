<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 13:48
 */

namespace AppBundle\Validator;

use AppBundle\Entity\CalendarData;
use AppBundle\Utils\EventUtils;
use AppBundle\Utils\Formatter;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CalendarDataEndMoreThanStartValidator extends ConstraintValidator
{
    /** @var  EventUtils */
    protected $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function validate($event, Constraint $constraint)
    {
        if (!$this->isEndMoreThanStart($event)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('workDayStart')
                ->addViolation();
        }
    }

    protected function isEndMoreThanStart(CalendarData $calendarData)
    {
        if (\DateTime::createFromFormat($this->formatter->getBackendTimeFormat(), $calendarData->getWorkDayStart()) <= \DateTime::createFromFormat($this->formatter->getBackendTimeFormat(), $calendarData->getWorkDayEnd())) {
            return true;
        }
        return false;
    }
}
