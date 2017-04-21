<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:39
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="concession")
 * @ORM\HasLifecycleCallbacks()
 */
class Concession
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
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var ConcessionPrice
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ConcessionPrice", mappedBy="concession", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $concessionPrices;

    public function __toString()
    {
        return $this->getName();
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
     * Set name
     *
     * @param string $name
     * @return Concession
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
     * Constructor
     */
    public function __construct()
    {
        $this->concessionPrices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add concessionPrices
     *
     * @param \AppBundle\Entity\ConcessionPrice $concessionPrice
     * @return Concession
     */
    public function addConcessionPrice(\AppBundle\Entity\ConcessionPrice $concessionPrice)
    {
        $this->concessionPrices[] = $concessionPrice;
        $concessionPrice->setConcession($this);

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
        $concessionPrice->setConcession(null);
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
