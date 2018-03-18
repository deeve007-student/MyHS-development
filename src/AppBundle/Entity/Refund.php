<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 12.03.18
 * Time: 13:40
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="refund")
 * @ORM\HasLifecycleCallbacks()
 */
class Refund
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
     * @var Invoice
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Invoice", inversedBy="refunds")
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id", nullable=true)
     */
    protected $invoice;

    /**
     * @var InvoiceRefund[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\InvoiceRefund", mappedBy="refund", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $items;

    /** @var double */
    protected $itemsTotal;

    /** @var double */
    protected $paymentsTotal;

    public function __construct()
    {
        $this->items = new ArrayCollection();
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
     * Set invoice
     *
     * @param \AppBundle\Entity\Invoice $invoice
     * @return Refund
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
     * @return InvoiceRefund[]|ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param InvoiceRefund $item
     * @return Refund
     */
    public function addItem(InvoiceRefund $item)
    {
        $this->items->add($item);
        $item->setRefund($this);
        return $this;
    }

    /**
     * @param InvoiceRefund $item
     * @return Refund
     */
    public function removeItem(InvoiceRefund $item)
    {
        $this->items->removeElement($item);
        $item->setRefund(null);
        return $this;
    }

    /**
     * @return float
     */
    public function getItemsTotal()
    {
        return $this->itemsTotal;
    }

    /**
     * @param float $itemsTotal
     * @return Refund
     */
    public function setItemsTotal($itemsTotal)
    {
        $this->itemsTotal = $itemsTotal;
        return $this;
    }

    /**
     * @return float
     */
    public function getPaymentsTotal()
    {
        return $this->paymentsTotal;
    }

    /**
     * @param float $paymentsTotal
     * @return Refund
     */
    public function setPaymentsTotal($paymentsTotal)
    {
        $this->paymentsTotal = $paymentsTotal;
        return $this;
    }



}
