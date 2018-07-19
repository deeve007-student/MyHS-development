<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 02.07.2018
 * Time: 15:39
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="appointment_patient")
 * @ORM\HasLifecycleCallbacks()
 */
class AppointmentPatient
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
     * @var Appointment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Appointment", inversedBy="appointmentPatients")
     * @ORM\JoinColumn(name="appointment_id", referencedColumnName="id", nullable=false)
     */
    protected $appointment;

    /**
     * @var Patient
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Patient", cascade={"persist"}, inversedBy="patientAppointments")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id", nullable=false)
     */
    protected $patient;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", length=255, nullable=true)
     */
    protected $newPatient;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", length=255, nullable=true)
     */
    protected $patientArrived = false;

    /**
     * @var Invoice
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Invoice", inversedBy="appointmentPatients", cascade={"persist"})
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $invoice;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", length=255, nullable=true)
     */
    protected $noShow = false;

    /**
     * @var TreatmentPackCredit
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TreatmentPackCredit")
     * @ORM\JoinColumn(name="treatment_pack_id", referencedColumnName="id", nullable=true)
     */
    protected $treatmentPackCredit;

    /**
     * @var TreatmentNote
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\TreatmentNote", inversedBy="appointmentPatient", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="treatment_note_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $treatmentNote;

    /**
     * @var NoShowMessage
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\NoShowMessage", mappedBy="appointmentPatient", cascade={"persist", "remove"})
     */
    protected $noShowMessage;

    public function __toString()
    {
        return (string)$this->getPatient();
    }

    public function __clone()
    {
        $this->id = null;
        $this->setTreatmentNote(null);
        $this->setInvoice(null);
        $this->setPatientArrived(false);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Appointment
     */
    public function getAppointment()
    {
        return $this->appointment;
    }

    /**
     * @param Appointment $appointment
     * @return AppointmentPatient
     */
    public function setAppointment(Appointment $appointment = null)
    {
        $this->appointment = $appointment;
        return $this;
    }

    /**
     * Set patient
     *
     * @param Patient $patient
     * @return AppointmentPatient
     */
    public function setPatient(Patient $patient = null)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * Get patient
     *
     * @return Patient
     */
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * Set newPatient
     *
     * @param boolean $newPatient
     * @return AppointmentPatient
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
     * @return bool
     */
    public function getPatientArrived()
    {
        return $this->patientArrived;
    }

    /**
     * @param bool $patientArrived
     * @return AppointmentPatient
     */
    public function setPatientArrived($patientArrived)
    {
        $this->patientArrived = $patientArrived;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNoShow()
    {
        return $this->noShow;
    }

    /**
     * @param bool $noShow
     * @return AppointmentPatient
     */
    public function setNoShow($noShow)
    {
        $this->noShow = $noShow;
        return $this;
    }

    /**
     * @return TreatmentPackCredit
     */
    public function getTreatmentPackCredit()
    {
        return $this->treatmentPackCredit;
    }

    /**
     * @param TreatmentPackCredit $treatmentPackCredit
     * @return AppointmentPatient
     */
    public function setTreatmentPackCredit($treatmentPackCredit)
    {
        $this->treatmentPackCredit = $treatmentPackCredit;
        return $this;
    }

    /**
     * Set treatmentNote
     *
     * @param TreatmentNote $treatmentNote
     * @return AppointmentPatient
     */
    public function setTreatmentNote(TreatmentNote $treatmentNote = null)
    {
        $this->treatmentNote = $treatmentNote;

        return $this;
    }

    /**
     * Get treatmentNote
     *
     * @return TreatmentNote
     */
    public function getTreatmentNote()
    {
        return $this->treatmentNote;
    }

    /**
     * @return NoShowMessage
     */
    public function getNoShowMessage()
    {
        return $this->noShowMessage;
    }

    /**
     * @param NoShowMessage $noShowMessage
     * @return AppointmentPatient
     */
    public function setNoShowMessage($noShowMessage)
    {
        $this->noShowMessage = $noShowMessage;
        return $this;
    }

    /**
     * Set invoice
     *
     * @param Invoice $invoice
     * @return AppointmentPatient
     */
    public function setInvoice(Invoice $invoice = null)
    {
        $this->invoice = $invoice;

        if ($invoice) {
            $invoice->addAppointmentPatient($this);
        }

        return $this;
    }

    /**
     * Get invoice
     *
     * @return Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

}