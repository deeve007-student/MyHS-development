<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 23:32
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="concession_price")
 * @ORM\HasLifecycleCallbacks()
 */
class ConcessionPrice
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
     * @var ConcessionPriceOwner
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ConcessionPriceOwner", inversedBy="concessionPrices")
     * @ORM\JoinColumn(name="concession_price_owner_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $concessionPriceOwner;

    /**
     * @var Concession
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Concession", inversedBy="concessionPrices", fetch="EAGER")
     * @ORM\JoinColumn(name="concession_id", referencedColumnName="id", nullable=false)
     */
    protected $concession;

    /**
     * @var double
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=false)
     */
    protected $price;


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
     * Set price
     *
     * @param double $price
     * @return ConcessionPrice
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

    /**
     * Set concessionPriceOwner
     *
     * @param \AppBundle\Entity\ConcessionPriceOwner $concessionPriceOwner
     * @return ConcessionPrice
     */
    public function setConcessionPriceOwner(\AppBundle\Entity\ConcessionPriceOwner $concessionPriceOwner = null)
    {
        $this->concessionPriceOwner = $concessionPriceOwner;

        return $this;
    }

    /**
     * Get concessionPriceOwner
     *
     * @return \AppBundle\Entity\ConcessionPriceOwner
     */
    public function getConcessionPriceOwner()
    {
        return $this->concessionPriceOwner;
    }

    /**
     * Set concession
     *
     * @param \AppBundle\Entity\Concession $concession
     * @return ConcessionPrice
     */
    public function setConcession(\AppBundle\Entity\Concession $concession = null)
    {
        $this->concession = $concession;

        return $this;
    }

    /**
     * Get concession
     *
     * @return \AppBundle\Entity\Concession
     */
    public function getConcession()
    {
        return $this->concession;
    }
}
