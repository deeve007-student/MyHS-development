<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 18:54
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="appointment")
 * @ORM\HasLifecycleCallbacks()
 */
class Appointment extends Event
{

    const NEW_PATIENT_COLOR = "#FFC300";
    const TREATMENT_NOTE_CREATED_COLOR = "#ADD8E6";
    const DEFAULT_COLOR = "#D3D3D3";

    const FUTURE_BOOKING_CLASS = "future-booking";
    const PATIENT_ARRIVED_CLASS = "patient-arrived";
    const INVOICE_CREATED_CLASS = "invoice-created";

    /**
     * @var Patient
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Patient", cascade={"persist"})
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id", nullable=false)
     */
    protected $patient;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", length=255, nullable=true)
     */
    protected $patientArrived;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", length=255, nullable=true)
     */
    protected $newPatient;

    /**
     * @var Treatment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Treatment", inversedBy="invoiceTreatments")
     * @ORM\JoinColumn(name="treatment_id", referencedColumnName="id", nullable=false)
     */
    protected $treatment;

    /**
     * @var Invoice
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Invoice", inversedBy="appointment")
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id", nullable=true)
     */
    protected $invoice;

    /**
     * @var TreatmentNote
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\TreatmentNote", inversedBy="appointment")
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


    public function __toString()
    {
        return (string)$this->getPatient();
    }

    public function getEventClass()
    {
        return Event::class;
    }

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    public function __clone()
    {
        $this->id = null;
        $this->setTreatmentNote(null);
        $this->setInvoice(null);
        $this->setPatientArrived(false);
        $this->setResource(null);
        $this->setDescription(null);
    }

    /**
     * Set patient
     *
     * @param \AppBundle\Entity\Patient $patient
     * @return Appointment
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
     * Set patientArrived
     *
     * @param boolean $patientArrived
     * @return Appointment
     */
    public function setPatientArrived($patientArrived)
    {
        $this->patientArrived = $patientArrived;

        return $this;
    }

    /**
     * Get patientArrived
     *
     * @return boolean
     */
    public function getPatientArrived()
    {
        return $this->patientArrived;
    }

    /**
     * Set invoice
     *
     * @param \AppBundle\Entity\Invoice $invoice
     * @return Appointment
     */
    public function setInvoice(\AppBundle\Entity\Invoice $invoice = null)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return \AppBundle\Entity\Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
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
     * Set newPatient
     *
     * @param boolean $newPatient
     * @return Appointment
     */
    public function setNewPatient($newPatient)
    {
        $this->newPatient = $newPatient;

        return $this;
    }

    /**
     * Get newPatient
     *
     * @return boolean
     */
    public function getNewPatient()
    {
        return $this->newPatient;
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
}
