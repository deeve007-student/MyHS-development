<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 13:54
 */

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

class CalendarSettingsDatesNotEqual extends Constraint
{
    public $message = 'app.calendar_settings.equal_dates';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
