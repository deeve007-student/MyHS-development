<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 05.03.18
 * Time: 18:38
 */

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

class ManualCommunicationCorrect extends Constraint {

    public $message = 'This value can not be empty';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}
