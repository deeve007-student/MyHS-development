<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 20.06.2017
 * Time: 17:35
 */


namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

class EventNotOverlap extends Constraint
{
    public $message = 'app.event.overlap';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
