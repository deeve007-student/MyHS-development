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
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="manual_communication")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable()
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
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="attachment", fileNameProperty="fileName")
     *
     * @var File
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $fileName;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     *
     * @var string
     */
    private $fileSize;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $originFileName;

    /**
     * @var CommunicationType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CommunicationType")
     * @ORM\JoinColumn(name="communication_type_id", referencedColumnName="id", nullable=false)
     */
    protected $communicationType;

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
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return ManualCommunication
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;

        if ($file) {
            $fnameParts = explode('_', $file->getFilename());
            unset($fnameParts[0]);
            $this->setOriginFileName(implode('_', $fnameParts));
            $this->setFileSize($file->getSize());
            $this->setCreatedAt(new \DateTime());
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return ManualCommunication
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set originFileName
     *
     * @param string $originFileName
     * @return ManualCommunication
     */
    public function setOriginFileName($originFileName)
    {
        $this->originFileName = $originFileName;

        return $this;
    }

    /**
     * Get originFileName
     *
     * @return string
     */
    public function getOriginFileName()
    {
        return $this->originFileName;
    }

    /**
     * Set fileSize
     *
     * @param integer $fileSize
     * @return ManualCommunication
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * Get fileSize
     *
     * @return integer
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }
}
