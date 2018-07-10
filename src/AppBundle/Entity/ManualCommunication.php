<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 05.08.2017
 * Time: 21:26
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="manual_communication")
 * @ORM\HasLifecycleCallbacks()
 */
class ManualCommunication
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
     * @var Patient
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Patient")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id", nullable=true)
     */
    protected $patient;

    /**
     * @var BulkPatientList
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\BulkPatientList")
     * @ORM\JoinColumn(name="bulk_patient_list_id", referencedColumnName="id", nullable=true)
     */
    protected $bulkPatientList;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $subject;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $message;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $sms;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $text;

    /**
     * @var CommunicationType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CommunicationType")
     * @ORM\JoinColumn(name="communication_type_id", referencedColumnName="id", nullable=false)
     */
    protected $communicationType;

    /**
     * @var ManualCommunicationAttachment[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ManualCommunicationAttachment", mappedBy="manualCommunication", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $manualCommunicationAttachments;

    public function __toString()
    {
        return (string)$this->getId();
    }

    public function __construct()
    {
        $this->manualCommunicationAttachments = new ArrayCollection();
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
     * Set patient
     *
     * @param Patient $patient
     * @return ManualCommunication
     */
    public function setPatient(Patient $patient = null)
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
     * Set text
     *
     * @param string $text
     * @return ManualCommunication
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
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return ManualCommunication
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return ManualCommunication
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getSms()
    {
        return $this->sms;
    }

    /**
     * @param string $sms
     * @return ManualCommunication
     */
    public function setSms($sms)
    {
        $this->sms = $sms;
        return $this;
    }

    /**
     * @return CommunicationType
     */
    public function getCommunicationType()
    {
        return $this->communicationType;
    }

    /**
     * @param CommunicationType $communicationType
     * @return ManualCommunication
     */
    public function setCommunicationType($communicationType)
    {
        $this->communicationType = $communicationType;
        return $this;
    }

    /**
     * @return BulkPatientList
     */
    public function getBulkPatientList()
    {
        return $this->bulkPatientList;
    }

    /**
     * @param BulkPatientList $bulkPatientList
     * @return ManualCommunication
     */
    public function setBulkPatientList($bulkPatientList)
    {
        $this->bulkPatientList = $bulkPatientList;
        return $this;
    }

    public function isAddresseeCorrect()
    {
        if (!$this->getPatient() && !$this->getBulkPatientList()) {
            return false;
        }
        return true;
    }

    /**
     * @return ManualCommunicationAttachment[]|ArrayCollection
     */
    public function getManualCommunicationAttachments()
    {
        return $this->manualCommunicationAttachments;
    }

    /**
     * @param ManualCommunicationAttachment $manualCommunicationAttachment
     * @return ManualCommunication
     */
    public function addManualCommunicationAttachment(ManualCommunicationAttachment $manualCommunicationAttachment)
    {
        $this->manualCommunicationAttachments->add($manualCommunicationAttachment);
        $manualCommunicationAttachment->setManualCommunication($this);
        return $this;
    }

    /**
     * @param ManualCommunicationAttachment $manualCommunicationAttachment
     * @return ManualCommunication
     */
    public function removeManualCommunicationAttachment(ManualCommunicationAttachment $manualCommunicationAttachment)
    {
        $this->manualCommunicationAttachments->removeElement($manualCommunicationAttachment);
        return $this;
    }


}
