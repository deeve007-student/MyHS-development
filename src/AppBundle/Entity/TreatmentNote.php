<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 19:37
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="treatment_note")
 */
class TreatmentNote extends TreatmentNoteFieldOwner
{

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var Patient
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Patient", inversedBy="treatmentNotes")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id", nullable=false)
     */
    protected $patient;

    /**
     * @var Appointment
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Appointment", mappedBy="treatmentNote")
     */
    protected $appointment;

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return TreatmentNote
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
     * Set patient
     *
     * @param \AppBundle\Entity\Patient $patient
     * @return TreatmentNote
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
     * Set appointment
     *
     * @param \AppBundle\Entity\Appointment $appointment
     * @return TreatmentNote
     */
    public function setAppointment(\AppBundle\Entity\Appointment $appointment = null)
    {
        $this->appointment = $appointment;
        $appointment->setTreatmentNote($this);

        return $this;
    }

    /**
     * Get appointment
     *
     * @return \AppBundle\Entity\Appointment
     */
    public function getAppointment()
    {
        return $this->appointment;
    }
}
