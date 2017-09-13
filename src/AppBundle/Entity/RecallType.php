<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 05.08.2017
 * Time: 21:26
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="recall_type")
 */
class RecallType
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", length=255, nullable=true)
     */
    protected $byCall;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", length=255, nullable=true)
     */
    protected $bySms;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", length=255, nullable=true)
     */
    protected $byEmail;

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
     * @param string $name
     * @return RecallType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isByCall()
    {
        return $this->byCall;
    }

    /**
     * @param bool $byCall
     * @return RecallType
     */
    public function setByCall($byCall)
    {
        $this->byCall = $byCall;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBySms()
    {
        return $this->bySms;
    }

    /**
     * @param bool $bySms
     * @return RecallType
     */
    public function setBySms($bySms)
    {
        $this->bySms = $bySms;

        return $this;
    }

    /**
     * @return bool
     */
    public function isByEmail()
    {
        return $this->byEmail;
    }

    /**
     * @param bool $byEmail
     * @return RecallType
     */
    public function setByEmail($byEmail)
    {
        $this->byEmail = $byEmail;

        return $this;
    }
    
}
