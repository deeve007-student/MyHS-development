<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 14.03.2017
 * Time: 18:49
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="treatment")
 */
class Treatment extends ConcessionPriceOwner
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
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $calendarColour;

    public function __toString()
    {
        if ($this->getCode()) {
            return $this->getName().' ('.$this->getCode().')';
        }

        return $this->getName();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Treatment
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
     * @return Treatment
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
     * Set description
     *
     * @param string $description
     * @return Treatment
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set calendarColour
     *
     * @param string $calendarColour
     * @return Treatment
     */
    public function setCalendarColour($calendarColour)
    {
        $this->calendarColour = $calendarColour;

        return $this;
    }

    /**
     * Get calendarColour
     *
     * @return string
     */
    public function getCalendarColour()
    {
        return $this->calendarColour;
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
     * @return Treatment
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
