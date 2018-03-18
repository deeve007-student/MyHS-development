<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 12.03.18
 * Time: 16:37
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="invoice_refund")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\HasLifecycleCallbacks()
 */
class InvoiceRefund
{
    use CreatedUpdatedTrait;
    use OwnerFieldTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var double
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=false)
     */
    protected $amount;

    /**
     * @var InvoicePaymentMethod
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\InvoicePaymentMethod")
     * @ORM\JoinColumn(name="invoice_payment_method_id", referencedColumnName="id", nullable=false)
     */
    protected $paymentMethod;

    /**
     * @var Refund
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Refund", inversedBy="items")
     * @ORM\JoinColumn(name="refund_id", referencedColumnName="id", nullable=false)
     */
    protected $refund;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return Refund
     */
    public function getRefund()
    {
        return $this->refund;
    }

    /**
     * @param Refund $refund
     * @return InvoiceRefund
     */
    public function setRefund($refund = null)
    {
        $this->refund = $refund;
        return $this;
    }

    /**
     * Set paymentMethod
     *
     * @param InvoicePaymentMethod $paymentMethod
     * @return InvoiceRefund
     */
    public function setPaymentMethod(InvoicePaymentMethod $paymentMethod = null)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get paymentMethod
     *
     * @return InvoicePaymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

}
