<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.05.2017
 * Time: 14:36
 */

namespace AppBundle\Twig;

use AppBundle\Entity\Invoice;
use Symfony\Component\Translation\Translator;

class InvoiceStatusExtension extends \Twig_Extension
{

    /** @var Translator  */
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('app_invoice_status_label', array($this, 'getLabel')),
            new \Twig_SimpleFilter('app_invoice_status_color', array($this, 'getColor')),
        );
    }

    public function getLabel($status)
    {
        return $this->translator->trans('app.invoice.statuses.'.$status);
    }

    public function getColor($status)
    {
        return Invoice::getColorClass($status);
    }
}
