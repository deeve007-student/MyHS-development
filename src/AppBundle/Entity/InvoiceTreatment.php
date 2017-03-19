<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 17:02
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="invoice_treatment")
 */
class InvoiceTreatment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Invoice
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Invoice", inversedBy="invoiceTreatments")
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id", nullable=false)
     */
    protected $invoice;

    /**
     * @var Treatment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Treatment")
     * @ORM\JoinColumn(name="treatment_id", referencedColumnName="id", nullable=false)
     */
    protected $treatment;

    /**
     * @var money
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=false)
     */
    protected $price;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $quantity;

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
     * @param money $price
     * @return InvoiceTreatment
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return money
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set invoice
     *
     * @param \AppBundle\Entity\Invoice $invoice
     * @return InvoiceTreatment
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
     * Set treatment
     *
     * @param \AppBundle\Entity\Treatment $treatment
     * @return InvoiceTreatment
     */
    public function setTreatment(\AppBundle\Entity\Treatment $treatment = null)
    {
        $this->treatment = $treatment;

        return $this;
    }

    /**
     * Get treatment
     *
     * @return \AppBundle\Entity\Treatment
     */
    public function getTreatment()
    {
        return $this->treatment;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return InvoiceTreatment
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
}
