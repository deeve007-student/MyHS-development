<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 12.03.18
 * Time: 13:30
 */

namespace AppBundle\Entity\Traits;

use AppBundle\Entity\Invoice;

trait InvoiceItemTrait
{


    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return bool
     */
    public function isFromOtherInvoice()
    {
        return $this->fromOtherInvoice;
    }

    /**
     * @param bool $fromOtherInvoice
     * @return $this
     */
    public function setFromOtherInvoice($fromOtherInvoice)
    {
        $this->fromOtherInvoice = $fromOtherInvoice;
        return $this;
    }

    /**
     * Set invoice
     *
     * @param \AppBundle\Entity\Invoice $invoice
     * @return $this
     */
    public function setInvoice(\AppBundle\Entity\Invoice $invoice = null)
    {
        $this->invoice = $invoice;

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
     * Get total
     *
     * @return money
     */
    public function getTotal()
    {
        return $this->getPrice() * $this->getQuantity();
    }

    /**
     * Set price
     *
     * @param double $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return double
     */
    public function getPrice()
    {
        return $this->price;
    }

    public function getPaidAmount()
    {
        /** @var Invoice $invoice */
        $invoice = $this->getInvoice();
        $paidAmount = $invoice->getPaymentsSum();
        $ratio = $paidAmount / $invoice->getTotal();
        return $this->getTotal() * $ratio;
    }

}
