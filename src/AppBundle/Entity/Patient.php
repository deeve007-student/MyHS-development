<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 11:01
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="patient")
 * @ORM\HasLifecycleCallbacks()
 */
class Patient
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
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $preferredName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $postCode;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $addressFirst;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $addressSecond;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    protected $dateOfBirth;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $gender;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Attachment", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\JoinTable(name="patient_attachment",
     *      joinColumns={@ORM\JoinColumn(name="patient_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="attachment_id", referencedColumnName="id", unique=true, onDelete="CASCADE")}
     *     )
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $attachments;

    /**
     * @var State
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\State")
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id", nullable=true)
     */
    protected $state;

    /**
     * @var RelatedPatient[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\RelatedPatient", mappedBy="mainPatient", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $relatedPatients;

    /**
     * @var Invoice[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Invoice", mappedBy="patient", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $invoices;

    /**
     * @var TreatmentNote[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TreatmentNote", mappedBy="patient", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $treatmentNotes;

    /**
     * @var Phone[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Phone", mappedBy="patient", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $phones;

    /**
     * @var PatientAlert[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PatientAlert", mappedBy="patient", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $alerts;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", length=255, nullable=true)
     */
    protected $autoRemindSMS;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", length=255, nullable=true)
     */
    protected $autoRemindEmail;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", length=255, nullable=true)
     */
    protected $bookingConfirmationEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $occupation;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $emergencyContact;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $healthFund;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $referrer;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $mobilePhone;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $notes;

    /**
     * @var Concession
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Concession")
     * @ORM\JoinColumn(name="concession_id", referencedColumnName="id", nullable=true)
     */
    protected $concession;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->relatedPatients = new \Doctrine\Common\Collections\ArrayCollection();
        $this->phones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->alerts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invoices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return trim($this->getTitle() . ' ' . $this->getFirstName() . ' ' . $this->getLastName());
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
     * Set firstName
     *
     * @param string $firstName
     * @return Patient
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Patient
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Patient
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set autoRemindSMS
     *
     * @param boolean $autoRemindSMS
     * @return Patient
     */
    public function setAutoRemindSMS($autoRemindSMS)
    {
        $this->autoRemindSMS = $autoRemindSMS;

        return $this;
    }

    /**
     * Get autoRemindSMS
     *
     * @return boolean
     */
    public function getAutoRemindSMS()
    {
        return $this->autoRemindSMS;
    }

    /**
     * Set autoRemindEmail
     *
     * @param boolean $autoRemindEmail
     * @return Patient
     */
    public function setAutoRemindEmail($autoRemindEmail)
    {
        $this->autoRemindEmail = $autoRemindEmail;

        return $this;
    }

    /**
     * Get autoRemindEmail
     *
     * @return boolean
     */
    public function getAutoRemindEmail()
    {
        return $this->autoRemindEmail;
    }

    /**
     * Set bookingConfirmationEmail
     *
     * @param boolean $bookingConfirmationEmail
     * @return Patient
     */
    public function setBookingConfirmationEmail($bookingConfirmationEmail)
    {
        $this->bookingConfirmationEmail = $bookingConfirmationEmail;

        return $this;
    }

    /**
     * Get bookingConfirmationEmail
     *
     * @return boolean
     */
    public function getBookingConfirmationEmail()
    {
        return $this->bookingConfirmationEmail;
    }

    /**
     * Set state
     *
     * @param \AppBundle\Entity\State $state
     * @return Patient
     */
    public function setState(\AppBundle\Entity\State $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return \AppBundle\Entity\State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set preferredName
     *
     * @param string $preferredName
     * @return Patient
     */
    public function setPreferredName($preferredName)
    {
        $this->preferredName = $preferredName;

        return $this;
    }

    /**
     * Get preferredName
     *
     * @return string
     */
    public function getPreferredName()
    {
        return $this->preferredName;
    }

    /**
     * Set occupation
     *
     * @param string $occupation
     * @return Patient
     */
    public function setOccupation($occupation)
    {
        $this->occupation = $occupation;

        return $this;
    }

    /**
     * Get occupation
     *
     * @return string
     */
    public function getOccupation()
    {
        return $this->occupation;
    }

    /**
     * Set emergencyContact
     *
     * @param string $emergencyContact
     * @return Patient
     */
    public function setEmergencyContact($emergencyContact)
    {
        $this->emergencyContact = $emergencyContact;

        return $this;
    }

    /**
     * Get emergencyContact
     *
     * @return string
     */
    public function getEmergencyContact()
    {
        return $this->emergencyContact;
    }

    /**
     * Set healthFund
     *
     * @param string $healthFund
     * @return Patient
     */
    public function setHealthFund($healthFund)
    {
        $this->healthFund = $healthFund;

        return $this;
    }

    /**
     * Get healthFund
     *
     * @return string
     */
    public function getHealthFund()
    {
        return $this->healthFund;
    }

    /**
     * Set referrer
     *
     * @param string $referrer
     * @return Patient
     */
    public function setReferrer($referrer)
    {
        $this->referrer = $referrer;

        return $this;
    }

    /**
     * Get referrer
     *
     * @return string
     */
    public function getReferrer()
    {
        return $this->referrer;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return Patient
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

    /**
     * Set email
     *
     * @param string $email
     * @return Patient
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set addressFirst
     *
     * @param string $addressFirst
     * @return Patient
     */
    public function setAddressFirst($addressFirst)
    {
        $this->addressFirst = $addressFirst;

        return $this;
    }

    /**
     * Get addressFirst
     *
     * @return string
     */
    public function getAddressFirst()
    {
        return $this->addressFirst;
    }

    /**
     * Set addressSecond
     *
     * @param string $addressSecond
     * @return Patient
     */
    public function setAddressSecond($addressSecond)
    {
        $this->addressSecond = $addressSecond;

        return $this;
    }

    /**
     * Get addressSecond
     *
     * @return string
     */
    public function getAddressSecond()
    {
        return $this->addressSecond;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     * @return Patient
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return Patient
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Add relatedPatient
     *
     * @param \AppBundle\Entity\RelatedPatient $relatedPatient
     * @return Patient
     */
    public function addRelatedPatient(\AppBundle\Entity\RelatedPatient $relatedPatient)
    {
        $this->relatedPatients[] = $relatedPatient;
        $relatedPatient->setMainPatient($this);

        return $this;
    }

    /**
     * Remove relatedPatient
     *
     * @param \AppBundle\Entity\RelatedPatient $relatedPatient
     */
    public function removeRelatedPatient(\AppBundle\Entity\RelatedPatient $relatedPatient)
    {
        $this->relatedPatients->removeElement($relatedPatient);
    }

    /**
     * Get relatedPatients
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRelatedPatients()
    {
        return $this->relatedPatients;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Patient
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Add phones
     *
     * @param \AppBundle\Entity\Phone $phone
     * @return Patient
     */
    public function addPhone(\AppBundle\Entity\Phone $phone)
    {
        $this->phones[] = $phone;
        $phone->setPatient($this);

        return $this;
    }

    /**
     * Remove phones
     *
     * @param \AppBundle\Entity\Phone $phone
     */
    public function removePhone(\AppBundle\Entity\Phone $phone)
    {
        $this->phones->removeElement($phone);
    }

    /**
     * Get phones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Add alert
     *
     * @param \AppBundle\Entity\PatientAlert $alert
     * @return Patient
     */
    public function addAlert(\AppBundle\Entity\PatientAlert $alert)
    {
        $this->alerts[] = $alert;
        $alert->setPatient($this);

        return $this;
    }

    /**
     * Remove alert
     *
     * @param \AppBundle\Entity\PatientAlert $alert
     */
    public function removeAlert(\AppBundle\Entity\PatientAlert $alert)
    {
        $this->alerts->removeElement($alert);
    }

    /**
     * Get alerts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlerts()
    {
        return $this->alerts;
    }


    /**
     * Add invoice
     *
     * @param \AppBundle\Entity\Invoice $invoice
     * @return Patient
     */
    public function addInvoice(\AppBundle\Entity\Invoice $invoice)
    {
        $this->invoices[] = $invoice;
        $invoice->setPatient($this);

        return $this;
    }

    /**
     * Remove invoice
     *
     * @param \AppBundle\Entity\Invoice $invoice
     */
    public function removeInvoice(\AppBundle\Entity\Invoice $invoice)
    {
        $this->invoices->removeElement($invoice);
    }

    /**
     * Get invoices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoices()
    {
        return $this->invoices;
    }

    public function getAddressFull()
    {
        return trim(
            $this->getCity() . ' ' . $this->getAddressFirst() . ' ' . $this->getAddressSecond()
        );
    }

    /**
     * Add attachments
     *
     * @param \AppBundle\Entity\Attachment $attachment
     * @return Patient
     */
    public function addAttachment(\AppBundle\Entity\Attachment $attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Remove attachments
     *
     * @param \AppBundle\Entity\Attachment $attachment
     */
    public function removeAttachment(\AppBundle\Entity\Attachment $attachment)
    {
        $this->attachments->removeElement($attachment);
    }

    /**
     * Get attachments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Set mobilePhone
     *
     * @param string $mobilePhone
     * @return Patient
     */
    public function setMobilePhone($mobilePhone)
    {
        $this->mobilePhone = $mobilePhone;

        return $this;
    }

    /**
     * Get mobilePhone
     *
     * @return string
     */
    public function getMobilePhone()
    {
        return $this->mobilePhone;
    }

    /**
     * Add treatmentNotes
     *
     * @param \AppBundle\Entity\TreatmentNote $treatmentNote
     * @return Patient
     */
    public function addTreatmentNote(\AppBundle\Entity\TreatmentNote $treatmentNote)
    {
        $this->treatmentNotes[] = $treatmentNote;
        $treatmentNote->setPatient($this);

        return $this;
    }

    /**
     * Remove treatmentNotes
     *
     * @param \AppBundle\Entity\TreatmentNote $treatmentNote
     */
    public function removeTreatmentNote(\AppBundle\Entity\TreatmentNote $treatmentNote)
    {
        $this->treatmentNotes->removeElement($treatmentNote);
        $treatmentNote->setPatient(null);
    }

    /**
     * Get treatmentNotes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTreatmentNotes()
    {
        return $this->treatmentNotes;
    }

    /**
     * Set concession
     *
     * @param \AppBundle\Entity\Concession $concession
     * @return Patient
     */
    public function setConcession(\AppBundle\Entity\Concession $concession = null)
    {
        $this->concession = $concession;

        return $this;
    }

    /**
     * Get concession
     *
     * @return \AppBundle\Entity\Concession
     */
    public function getConcession()
    {
        return $this->concession;
    }

    /**
     * @param string $postCode
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;
    }

    /**
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    public function getPreferredNotificationMethod()
    {
        if ($this->getEmail()) {
            return Message::TYPE_EMAIL;
        }
        if ($this->getMobilePhone()) {
            return Message::TYPE_SMS;
        }
        throw new \Exception('Patient does not have neither email nor mobile phone fields filled');
    }

}
