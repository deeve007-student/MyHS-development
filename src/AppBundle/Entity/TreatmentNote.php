<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 19:37
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="treatment_note")
 */
class TreatmentNote extends TreatmentNoteFieldOwner
{

    const STATUS_DRAFT = 'draft';
    const STATUS_FINAL = 'final';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $status;

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

    public function __clone()
    {
        $this->id = null;

        $this->setStatus(self::STATUS_DRAFT);

        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());

        $treatmentNoteFieldsClone = new ArrayCollection();
        if ($this->treatmentNoteFields) {
            foreach ($this->treatmentNoteFields as $treatmentNoteField) {
                $treatmentNoteFieldClone = clone $treatmentNoteField;
                $treatmentNoteFieldClone->setTreatmentNoteFieldOwner($this);
                $treatmentNoteFieldsClone->add($treatmentNoteFieldClone);
            }
        }
        $this->treatmentNoteFields = $treatmentNoteFieldsClone;
    }

    public function __toString()
    {
        return (string)$this->getOwner();
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

        if ($appointment) {
            $appointment->setTreatmentNote($this);
        } else {
            $this->getAppointment()->setTreatmentNote(null);
        }

        $this->appointment = $appointment;

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

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        $empty = true;
        foreach ($this->getTreatmentNoteFields() as $noteField) {
            if (!($noteField->getValue() == '' || !$noteField->getValue())) {
                $empty = false;
            }
        }
        return $empty;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
