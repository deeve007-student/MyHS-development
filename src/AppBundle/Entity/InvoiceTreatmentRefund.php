<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 12.03.18
 * Time: 16:19
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\InvoiceRefundTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="invoice_refund_treatment")
 * @ORM\HasLifecycleCallbacks()
 */
class InvoiceTreatmentRefund  extends InvoiceRefund
{

    use InvoiceRefundTrait;

    /**
     * @var InvoiceTreatment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\InvoiceTreatment", inversedBy="refunds")
     * @ORM\JoinColumn(name="invoice_treatment_id", referencedColumnName="id", nullable=false)
     */
    protected $item;

}
