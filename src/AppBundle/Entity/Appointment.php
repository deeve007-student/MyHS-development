<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 18:54
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="event_appointment")
 * @ORM\HasLifecycleCallbacks()
 */
class Appointment extends Event
{

    const NEW_PATIENT_COLOR = "#FFC300";
    const TREATMENT_NOTE_CREATED_COLOR = "#ADD8E6";
    const DEFAULT_COLOR = "#D3D3D3";

    const FUTURE_BOOKING_CLASS = "future-booking";
    const PATIENT_ARRIVED_CLASS = "patient-arrived";
    const INVOICE_PAID_CLASS = "invoice-created";

    /**
     * @var Treatment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Treatment", inversedBy="invoiceTreatments")
     * @ORM\JoinColumn(name="treatment_id", referencedColumnName="id", nullable=false)
     */
    protected $treatment;

    /**
     * @var TreatmentNote
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\TreatmentNote", inversedBy="appointment", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="treatment_note_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $treatmentNote;

    /**
     * @var CancelReason
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CancelReason")
     * @ORM\JoinColumn(name="cancel_reason_id", referencedColumnName="id", nullable=true)
     */
    protected $reason;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $lastEventClass;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $lastEventPrevClass;

    /**
     * @var integer
     */
    protected $packId;

    /**
     * @var AppointmentPatient[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AppointmentPatient", mappedBy="appointment", cascade={"persist","remove"},orphanRemoval=true)
     */
    protected $appointmentPatients;

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->getAppointmentPatients()->count() === 1) {
            $patients = array_map(function (AppointmentPatient $appointmentPatient) {
                return (string)$appointmentPatient->getPatient();
            }, $this->getAppointmentPatients()->toArray());

            $name = implode(', ', $patients);
            if ($this->getTreatment()->getCode()) {
                $name .= ' (' . $this->getTreatment()->getCode() . ')';
            }
            return $name;
        }

        return $this->getTreatment()->getCode() . ' (' . $this->getAppointmentPatients()->count() . ')';
    }

    /**
     * @return string
     */
    public function getPatientsList()
    {
        $patients = array_map(function (AppointmentPatient $appointmentPatient) {
            return (string)$appointmentPatient->getPatient();
        }, $this->getAppointmentPatients()->toArray());

        return implode(', ', $patients);
    }

    public function getEventClass()
    {
        return Event::class;
    }

    /**
     * Appointment constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->appointmentPatients = new ArrayCollection();
    }

    public function __clone()
    {
        $this->id = null;
        $this->setResource(null);
        $this->setDescription(null);

        $appointmentPatientsClone = new ArrayCollection();
        if ($this->appointmentPatients) {
            foreach ($this->appointmentPatients as $appointmentPatient) {
                $appointmentPatientClone = clone $appointmentPatient;
                $appointmentPatientClone->setAppointment($this);
                $appointmentPatientsClone->add($appointmentPatientClone);
            }
        }
        $this->appointmentPatients = $appointmentPatientsClone;
    }

    /**
     * Set treatment
     *
     * @param \AppBundle\Entity\Treatment $treatment
     * @return Appointment
     */
    public function setTreatment(\AppBundle\Entity\Treatment $treatment = null)
    {
        $this->treatment = $treatment;

        return $this;
    }

    /**
     * Get treatment
     *
     * @return \AppBundle\Entity\Treatment
     */
    public function getTreatment()
    {
        return $this->treatment;
    }

    /**
     * Set treatmentNote
     *
     * @param \AppBundle\Entity\TreatmentNote $treatmentNote
     * @return Appointment
     */
    public function setTreatmentNote(\AppBundle\Entity\TreatmentNote $treatmentNote = null)
    {
        $this->treatmentNote = $treatmentNote;

        return $this;
    }

    /**
     * Get treatmentNote
     *
     * @return \AppBundle\Entity\TreatmentNote
     */
    public function getTreatmentNote()
    {
        return $this->treatmentNote;
    }

    /**
     * Set reason
     *
     * @param \AppBundle\Entity\CancelReason $reason
     * @return Appointment
     */
    public function setReason(\AppBundle\Entity\CancelReason $reason = null)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return \AppBundle\Entity\CancelReason
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set lastEventColor
     *
     * @param string $lastEventClass
     * @return Appointment
     */
    public function setLastEventClass($lastEventClass = null)
    {
        $this->lastEventClass = $lastEventClass;
        return $this;
    }

    /**
     * Get lastEventColor
     *
     * @return string
     */
    public function getLastEventClass()
    {
        return $this->lastEventClass;
    }

    /**
     * @return string
     */
    public function getLastEventPrevClass()
    {
        return $this->lastEventPrevClass;
    }

    /**
     * @param string $lastEventPrevClass
     * @return Appointment
     */
    public function setLastEventPrevClass($lastEventPrevClass = null)
    {
        $this->lastEventPrevClass = $lastEventPrevClass;
        return $this;
    }

    /**
     * @return int
     */
    public function getPackId()
    {
        return $this->packId;
    }

    /**
     * @param int $packId
     * @return Appointment
     */
    public function setPackId($packId)
    {
        $this->packId = $packId;
        return $this;
    }

    /**
     * @param AppointmentPatient $appointmentPatient
     * @return Appointment
     */
    public function addAppointmentPatient(AppointmentPatient $appointmentPatient)
    {
        $appointmentPatient->setAppointment($this);
        $this->appointmentPatients->add($appointmentPatient);
        return $this;
    }

    /**
     * @param AppointmentPatient $appointmentPatient
     * @return Appointment
     */
    public function removeAppointmentPatient(AppointmentPatient $appointmentPatient)
    {
        $this->appointmentPatients->removeElement($appointmentPatient);
        return $this;
    }

    /**
     * @return AppointmentPatient[]|ArrayCollection
     */
    public function getAppointmentPatients()
    {
        return $this->appointmentPatients;
    }

}
