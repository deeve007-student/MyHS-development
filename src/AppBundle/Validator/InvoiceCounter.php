<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.11.17
 * Time: 16:54
 */

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

class InvoiceCounter extends Constraint
{
    public $message = 'app.invoice_settings.wrong_invoice_number';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
