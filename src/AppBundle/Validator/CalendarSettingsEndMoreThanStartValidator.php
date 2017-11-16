<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 13:48
 */

namespace AppBundle\Validator;

use AppBundle\Entity\CalendarSettings;
use AppBundle\Utils\EventUtils;
use AppBundle\Utils\Formatter;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CalendarSettingsEndMoreThanStartValidator extends ConstraintValidator
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

    protected function isEndMoreThanStart(CalendarSettings $calendarSettings)
    {
        if (\DateTime::createFromFormat($this->formatter->getBackendTimeFormat(), $calendarSettings->getWorkDayStart()) <= \DateTime::createFromFormat($this->formatter->getBackendTimeFormat(), $calendarSettings->getWorkDayEnd())) {
            return true;
        }
        return false;
    }
}
