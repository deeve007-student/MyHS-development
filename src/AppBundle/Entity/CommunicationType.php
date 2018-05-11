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
 * @ORM\Table(name="communication_type")
 */
class CommunicationType
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
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $translation;

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
     * @return CommunicationType
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
    public function isBySms()
    {
        return $this->bySms;
    }

    /**
     * @param bool $bySms
     * @return CommunicationType
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
     * @return CommunicationType
     */
    public function setByEmail($byEmail)
    {
        $this->byEmail = $byEmail;

        return $this;
    }

    /**
     * @return string
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * @param string $translation
     * @return CommunicationType
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;
        return $this;
    }


    
}
