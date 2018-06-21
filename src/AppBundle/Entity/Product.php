<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:10
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product extends ConcessionPriceOwner
{

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $code;

    /**
     * @var InvoiceProduct[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\InvoiceProduct", mappedBy="product", cascade={"remove"}, orphanRemoval=true)
     */
    protected $invoiceProducts;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $supplier;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $costPrice;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $stockLevel;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $packAmount;

    /**
     * @var double
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $singleTreatmentPrice;

    /**
     * @var Treatment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Treatment")
     * @ORM\JoinColumn(name="treatment_id", referencedColumnName="id", nullable=true)
     */
    protected $treatment;

    public function __toString()
    {
        if ($this->getTreatment() && !$this->getName()) {
            return 'Pack of ' . $this->getTreatment()->getFullName();
        }
        return $this->getName();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Product
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set supplier
     *
     * @param string $supplier
     * @return Product
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * Get supplier
     *
     * @return string
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Set costPrice
     *
     * @param string $costPrice
     * @return Product
     */
    public function setCostPrice($costPrice)
    {
        $this->costPrice = $costPrice;

        return $this;
    }

    /**
     * Get costPrice
     *
     * @return string
     */
    public function getCostPrice()
    {
        return $this->costPrice;
    }

    /**
     * Set stockLevel
     *
     * @param integer $stockLevel
     * @return Product
     */
    public function setStockLevel($stockLevel)
    {
        $this->stockLevel = $stockLevel;

        return $this;
    }

    /**
     * Get stockLevel
     *
     * @return integer
     */
    public function getStockLevel()
    {
        return $this->stockLevel;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->concessionPrices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invoiceProducts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add concessionPrices
     *
     * @param \AppBundle\Entity\ConcessionPrice $concessionPrices
     * @return Product
     */
    public function addConcessionPrice(\AppBundle\Entity\ConcessionPrice $concessionPrices)
    {
        $this->concessionPrices[] = $concessionPrices;

        return $this;
    }

    /**
     * Remove concessionPrices
     *
     * @param \AppBundle\Entity\ConcessionPrice $concessionPrices
     */
    public function removeConcessionPrice(\AppBundle\Entity\ConcessionPrice $concessionPrices)
    {
        $this->concessionPrices->removeElement($concessionPrices);
    }

    /**
     * Get concessionPrices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConcessionPrices()
    {
        return $this->concessionPrices;
    }

    /**
     * Add invoiceProducts
     *
     * @param \AppBundle\Entity\InvoiceProduct $invoiceProducts
     * @return Product
     */
    public function addInvoiceProduct(\AppBundle\Entity\InvoiceProduct $invoiceProducts)
    {
        $this->invoiceProducts[] = $invoiceProducts;

        return $this;
    }

    /**
     * Remove invoiceProducts
     *
     * @param \AppBundle\Entity\InvoiceProduct $invoiceProducts
     */
    public function removeInvoiceProduct(\AppBundle\Entity\InvoiceProduct $invoiceProducts)
    {
        $this->invoiceProducts->removeElement($invoiceProducts);
    }

    /**
     * Get invoiceProducts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoiceProducts()
    {
        return $this->invoiceProducts;
    }

    /**
     * @return int
     */
    public function getPackAmount()
    {
        return $this->packAmount;
    }

    /**
     * @param int $packAmount
     * @return Product
     */
    public function setPackAmount($packAmount)
    {
        $this->packAmount = $packAmount;
        return $this;
    }

    /**
     * @return Treatment
     */
    public function getTreatment()
    {
        return $this->treatment;
    }

    /**
     * @param Treatment $treatment
     * @return Product
     */
    public function setTreatment(Treatment $treatment = null)
    {
        $this->treatment = $treatment;
        return $this;
    }

    /**
     * @return float
     */
    public function getSingleTreatmentPrice()
    {
        return $this->singleTreatmentPrice;
    }

    /**
     * @param float $singleTreatmentPrice
     * @return Product
     */
    public function setSingleTreatmentPrice($singleTreatmentPrice)
    {
        $this->singleTreatmentPrice = $singleTreatmentPrice;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPack()
    {
        return $this->getTreatment() ? true : false;
    }

    /**
     * Get price getter override (for treatment pack)
     *
     * @param Concession|null $concession
     * @return double
     */
    public function getPrice(Concession $concession = null)
    {
        if ($concession) {
            foreach ($this->getConcessionPrices() as $concessionPrice) {
                if ($concessionPrice->getConcession() == $concession) {

                    if ($this->isPack()) {
                        return $concessionPrice->getPrice() * $this->getPackAmount();
                    }
                    return $concessionPrice->getPrice();

                }
            }
        }

        if ($this->isPack()) {
            return $this->getSingleTreatmentPrice() * $this->getPackAmount();
        }
        return $this->price;
    }

}
