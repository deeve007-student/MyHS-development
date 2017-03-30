<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 23:19
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="concession_price_owner")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 */
class ConcessionPriceOwner
{
    use OwnerFieldTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=false)
     */
    protected $price;

    /**
     * @var ConcessionPrice
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ConcessionPrice", mappedBy="concessionPriceOwner", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $concessionPrices;

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
     * Constructor
     */
    public function __construct()
    {
        $this->concessionPrices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add concessionPrice
     *
     * @param \AppBundle\Entity\ConcessionPrice $concessionPrice
     * @return ConcessionPriceOwner
     */
    public function addConcessionPrice(\AppBundle\Entity\ConcessionPrice $concessionPrice)
    {
        $this->concessionPrices[] = $concessionPrice;
        $concessionPrice->setConcessionPriceOwner($this);

        return $this;
    }

    /**
     * Remove concessionPrices
     *
     * @param \AppBundle\Entity\ConcessionPrice $concessionPrice
     */
    public function removeConcessionPrice(\AppBundle\Entity\ConcessionPrice $concessionPrice)
    {
        $this->concessionPrices->removeElement($concessionPrice);
        $concessionPrice->setConcessionPriceOwner(null);
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
     * Set price
     *
     * @param string $price
     * @return Treatment
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }
}
