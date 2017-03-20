<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 06.03.2017
 * Time: 11:18
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="patient_alert")
 */
class PatientAlert
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
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $text;

    /**
     * @var Patient
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Patient", inversedBy="alerts")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id", nullable=false)
     */
    protected $patient;

    public function __toString()
    {
        return $this->getText();
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
     * Set text
     *
     * @param string $text
     * @return PatientAlert
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set patient
     *
     * @param \AppBundle\Entity\Patient $patient
     * @return PatientAlert
     */
    public function setPatient(\AppBundle\Entity\Patient $patient = null)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * Get patient
     *
     * @return \AppBundle\Entity\Patient 
     */
    public function getPatient()
    {
        return $this->patient;
    }
}
