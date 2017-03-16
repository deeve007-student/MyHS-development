<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 13:19
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="invoice")
 */
class Invoice
{

    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_PAID = 'paid';

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
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $status;

    /**
     * @var Patient
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Patient", inversedBy="invoices")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id", nullable=false)
     */
    protected $patient;

    /**
     * @var Product
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\InvoiceProduct", mappedBy="invoice", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $invoiceProducts;

    /**
     * @var Product
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\InvoiceTreatment", mappedBy="invoice", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $invoiceTreatments;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $patientAddress;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $notes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=false)
     */
    protected $date;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $reminderFrequency;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $dueDate;

    public function __clone()
    {
        $invoiceProductsClone = new ArrayCollection();
        if ($this->invoiceProducts) {
            foreach ($this->invoiceProducts as $invoiceProduct) {
                $invoiceProductClone = clone $invoiceProduct;
                $invoiceProductClone->setInvoice($this);
                $invoiceProductsClone->add($invoiceProductClone);
            }
        }
        $this->invoiceProducts = $invoiceProductsClone;

        $invoiceTreatmentsClone = new ArrayCollection();
        if ($this->invoiceTreatments) {
            foreach ($this->invoiceTreatments as $invoiceTreatment) {
                $invoiceTreatmentClone = clone $invoiceTreatment;
                $invoiceTreatmentClone->setInvoice($this);
                $invoiceTreatmentsClone->add($invoiceTreatmentClone);
            }
        }
        $this->invoiceTreatments = $invoiceTreatmentsClone;
    }

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
     * Set name
     *
     * @param string $name
     * @return Invoice
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
     * Set date
     *
     * @param \DateTime $date
     * @return Invoice
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Invoice
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set dueDate
     *
     * @param integer $dueDate
     * @return Invoice
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return integer
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Get dueDate computed
     *
     * @return \DateTime
     */
    public function getDueDateComputed()
    {
        $date = $this->getDate();
        
        $dueDateComputed = clone $date;
        $dueDateComputed = $dueDateComputed->modify('+ '.$this->getDueDate().'days');

        return $dueDateComputed;
    }

    /**
     * Set reminderFrequency
     *
     * @param integer $reminderFrequency
     * @return Invoice
     */
    public function setReminderFrequency($reminderFrequency)
    {
        $this->reminderFrequency = $reminderFrequency;

        return $this;
    }

    /**
     * Get reminderFrequency
     *
     * @return integer
     */
    public function getReminderFrequency()
    {
        return $this->reminderFrequency;
    }

    /**
     * Set patientAddress
     *
     * @param string $patientAddress
     * @return Invoice
     */
    public function setPatientAddress($patientAddress)
    {
        $this->patientAddress = $patientAddress;

        return $this;
    }

    /**
     * Get patientAddress
     *
     * @return string
     */
    public function getPatientAddress()
    {
        return $this->patientAddress;
    }

    /**
     * Set patient
     *
     * @param \AppBundle\Entity\Patient $patient
     * @return Invoice
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
     * Constructor
     */
    public function __construct()
    {
        $this->invoiceProducts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invoiceTreatments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add invoiceProducts
     *
     * @param \AppBundle\Entity\InvoiceProduct $invoiceProducts
     * @return Invoice
     */
    public function addInvoiceProduct(\AppBundle\Entity\InvoiceProduct $invoiceProducts)
    {
        $this->invoiceProducts[] = $invoiceProducts;
        $invoiceProducts->setInvoice($this);

        return $this;
    }

    /**
     * Remove invoiceProducts
     *
     * @param \AppBundle\Entity\InvoiceProduct $invoiceProducts
     */
    public function removeInvoiceProduct(\AppBundle\Entity\InvoiceProduct $invoiceProducts)
    {
        $this->invoiceProducts->removeElement($invoiceProducts);
    }

    /**
     * Get invoiceProducts
     *
     * @return \Doctrine\Common\Collections\Collection|InvoiceProduct[]
     */
    public function getInvoiceProducts()
    {
        return $this->invoiceProducts;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return Invoice
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    public function getAvailableStatuses()
    {
        switch ($this->getStatus()) {
            case self::STATUS_DRAFT:
                return array(self::STATUS_PENDING);
                break;
            case self::STATUS_PENDING:
                return array(self::STATUS_PAID);
                break;
            case self::STATUS_OVERDUE:
                return array(self::STATUS_PAID);
                break;
            case self::STATUS_PAID:
                return array();
                break;
        }

        return array();
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->getInvoiceProducts() as $product) {
            $total += $product->getTotal();
        }
        foreach ($this->getInvoiceTreatments() as $treatment) {
            $total += $treatment->getTotal();
        }

        return $total;
    }

    /**
     * Add invoiceTreatment
     *
     * @param \AppBundle\Entity\InvoiceTreatment $invoiceTreatment
     * @return Invoice
     */
    public function addInvoiceTreatment(\AppBundle\Entity\InvoiceTreatment $invoiceTreatment)
    {
        $this->invoiceTreatments[] = $invoiceTreatment;
        $invoiceTreatment->setInvoice($this);

        return $this;
    }

    /**
     * Remove invoiceTreatment
     *
     * @param \AppBundle\Entity\InvoiceTreatment $invoiceTreatment
     */
    public function removeInvoiceTreatment(\AppBundle\Entity\InvoiceTreatment $invoiceTreatment)
    {
        $this->invoiceTreatments->removeElement($invoiceTreatment);
    }

    /**
     * Get invoiceTreatments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoiceTreatments()
    {
        return $this->invoiceTreatments;
    }
}