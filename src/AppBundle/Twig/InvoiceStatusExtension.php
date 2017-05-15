<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.05.2017
 * Time: 14:36
 */

namespace AppBundle\Twig;

use AppBundle\Entity\Invoice;

class InvoiceStatusExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('app_invoice_status_label', array($this, 'getLabel')),
            new \Twig_SimpleFilter('app_invoice_status_color', array($this, 'getColor')),
        );
    }

    public function getLabel($status)
    {
        return Invoice::getColorClass($status);
    }

    public function getColor($status)
    {
        return Invoice::getColorClass($status);
    }
}
