<?php
/**
 * Created by PhpStorm.
 * User: stepa
 * Date: 02.04.2018
 * Time: 18:14
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\InvoiceItemTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="treatment_pack_credit")
 * @ORM\HasLifecycleCallbacks()
 */
class TreatmentPackCredit
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
     * @var InvoiceProduct
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\InvoiceProduct")
     * @ORM\JoinColumn(name="invoice_product_id", referencedColumnName="id", nullable=false)
     */
    protected $invoiceProduct;

    /**
     * @var Patient
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Patient", inversedBy="treatmentPackCredits")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id", nullable=false)
     */
    protected $patient;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $amountSpend;

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
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getInvoiceProduct()->getProduct();
    }

    /**
     * @return InvoiceProduct
     */
    public function getInvoiceProduct()
    {
        return $this->invoiceProduct;
    }

    /**
     * @param InvoiceProduct $invoiceProduct
     * @return TreatmentPackCredit
     */
    public function setInvoiceProduct(InvoiceProduct $invoiceProduct)
    {
        $this->invoiceProduct = $invoiceProduct;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmountSpend()
    {
        return $this->amountSpend;
    }

    /**
     * @param int $amountSpend
     * @return TreatmentPackCredit
     */
    public function setAmountSpend($amountSpend)
    {
        $this->amountSpend = $amountSpend;
        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->getInvoiceProduct()->getProduct();
    }

    /**
     * @return Treatment
     */
    public function getTreatment()
    {
        return $this->getProduct()->getTreatment();
    }

    /**
     * @return integer
     */
    public function getCreditsRemaining()
    {
        return ($this->getProduct()->getPackAmount() * $this->getInvoiceProduct()->getQuantity()) - $this->getAmountSpend();
    }

    /**
     * @return double
     */
    public function getPriceRemaining()
    {
        return $this->getCreditsRemaining() * ($this->getProduct()->getPrice($this->getPatient()->getConcession()) / $this->getProduct()->getPackAmount());
    }

    /**
     * @return Patient
     */
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * @param Patient $patient
     * @return TreatmentPackCredit
     */
    public function setPatient($patient)
    {
        $this->patient = $patient;
        return $this;
    }

}
