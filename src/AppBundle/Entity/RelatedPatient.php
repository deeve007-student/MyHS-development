<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 12:46
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="patient_related")
 */
class RelatedPatient
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Patient
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Patient", inversedBy="relatedPatients")
     * @ORM\JoinColumn(name="main_patient_id", referencedColumnName="id", nullable=false)
     */
    protected $mainPatient;

    /**
     * @var Patient
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Patient", inversedBy="parentRelatedPatients")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id", nullable=false)
     */
    protected $patient;

    /**
     * @var Patient
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PatientRelationship")
     * @ORM\JoinColumn(name="patient_relationship_id", referencedColumnName="id", nullable=false)
     */
    protected $patientRelationship;

    public function __toString()
    {
        return (string)$this->getId();
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
     * Set mainPatient
     *
     * @param \AppBundle\Entity\Patient $mainPatient
     * @return RelatedPatient
     */
    public function setMainPatient(\AppBundle\Entity\Patient $mainPatient = null)
    {
        $this->mainPatient = $mainPatient;

        return $this;
    }

    /**
     * Get mainPatient
     *
     * @return \AppBundle\Entity\Patient 
     */
    public function getMainPatient()
    {
        return $this->mainPatient;
    }

    /**
     * Set patient
     *
     * @param \AppBundle\Entity\Patient $patient
     * @return RelatedPatient
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


    /**
     * Set patientRelationship
     *
     * @param \AppBundle\Entity\PatientRelationship $patientRelationship
     * @return RelatedPatient
     */
    public function setPatientRelationship(\AppBundle\Entity\PatientRelationship $patientRelationship)
    {
        $this->patientRelationship = $patientRelationship;

        return $this;
    }

    /**
     * Get patientRelationship
     *
     * @return \AppBundle\Entity\PatientRelationship 
     */
    public function getPatientRelationship()
    {
        return $this->patientRelationship;
    }
}
