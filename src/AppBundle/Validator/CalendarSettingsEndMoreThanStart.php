<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 13:48
 */

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

class CalendarSettingsEndMoreThanStart extends Constraint
{
    public $message = 'app.calendar_settings.end_more_than_start';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
