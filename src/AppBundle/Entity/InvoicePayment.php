<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 02.07.2017
 * Time: 12:39
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="invoice_payment")
 * @ORM\HasLifecycleCallbacks()
 */
class InvoicePayment
{

    use OwnerFieldTrait;
    use CreatedUpdatedTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var InvoicePaymentMethod
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\InvoicePaymentMethod")
     * @ORM\JoinColumn(name="invoice_payment_method_id", referencedColumnName="id", nullable=false)
     */
    protected $paymentMethod;

    /**
     * @var Invoice
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Invoice", inversedBy="payments")
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id", nullable=false)
     */
    protected $invoice;

    /**
     * @var double
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=false)
     */
    protected $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=false)
     */
    protected $date;

    public function __toString()
    {
        return (string)$this->getPaymentMethod();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set paymentMethod
     *
     * @param InvoicePaymentMethod $paymentMethod
     * @return InvoicePayment
     */
    public function setPaymentMethod(InvoicePaymentMethod $paymentMethod)
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

    /**
     * Set invoice
     *
     * @param \AppBundle\Entity\Invoice $invoice
     * @return InvoicePayment
     */
    public function setInvoice(\AppBundle\Entity\Invoice $invoice)
    {
        $this->invoice = $invoice;
        if (!$invoice->getPayments()->contains($this)) {
            $invoice->addPayment($this);
        }

        return $this;
    }

    /**
     * Get invoice
     *
     * @return \AppBundle\Entity\Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Set amount
     *
     * @param double $amount
     * @return InvoicePayment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return double
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return boolean
     */
    public function isAmountCorrect()
    {
        if ($this->getInvoice()->getPaymentsSum() > $this->getInvoice()->getTotal()) {
            return false;
        }
        return true;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return InvoicePayment
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
