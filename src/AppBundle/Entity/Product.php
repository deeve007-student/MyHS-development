<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:10
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\OwnerFieldTrait;
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
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $code;

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

    public function __toString()
    {
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
        $this->concessionPrices = new \Doctrine\Common\Collections\ArrayCollection();
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
}
