<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.09.2017
 * Time: 13:34
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sms_cost")
 * @ORM\HasLifecycleCallbacks()
 */
class SmsCost
{

    use CreatedUpdatedTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", nullable=false)
     */
    protected $country;

    /**
     * @var double
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $inboundCost;

    /**
     * @var double
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $outboundCost;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=false)
     */
    protected $date;

    public function __toString()
    {
        return (string)$this->getCountry();
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
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     * @return SmsCost
     */
    public function setCountry(\AppBundle\Entity\Country $country = null)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return \AppBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return SmsCost
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

    /**
     * @return float
     */
    public function getInboundCost()
    {
        return $this->inboundCost;
    }

    /**
     * @param float $inboundCost
     * @return SmsCost
     */
    public function setInboundCost($inboundCost)
    {
        $this->inboundCost = $inboundCost;
        return $this;
    }

    /**
     * @return float
     */
    public function getOutboundCost()
    {
        return $this->outboundCost;
    }

    /**
     * @param float $outboundCost
     * @return SmsCost
     */
    public function setOutboundCost($outboundCost)
    {
        $this->outboundCost = $outboundCost;
        return $this;
    }
}
