<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.11.17
 * Time: 15:13
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="communications_settings")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable()
 */
class CommunicationsSettings
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
    protected $fromEmailAddress;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $appointmentCreationEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $appointmentCreationSms;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $newPatientFirstAppointmentEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $appointmentReminderEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $appointmentReminderSms;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $recallEmailSubject;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $recallEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $noShowSubject;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $noShowEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $recallSms;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $whenRemainderEmailSentDay;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $whenRemainderEmailSentTime;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $whenRemainderSmsSentDay;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="attachment", fileNameProperty="fileName")
     *
     * @var File
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $fileName;

    /**
     * @ORM\Column(type="integer", length=255)
     *
     * @var string
     */
    private $fileSize;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $originFileName;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $whenRemainderSmsSentTime;

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
     * @return string
     */
    public function getFromEmailAddress()
    {
        return $this->fromEmailAddress;
    }

    /**
     * @param string $fromEmailAddress
     * @return CommunicationsSettings
     */
    public function setFromEmailAddress($fromEmailAddress)
    {
        $this->fromEmailAddress = $fromEmailAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getAppointmentCreationEmail()
    {
        return $this->appointmentCreationEmail;
    }

    /**
     * @param string $appointmentCreationEmail
     * @return CommunicationsSettings
     */
    public function setAppointmentCreationEmail($appointmentCreationEmail)
    {
        $this->appointmentCreationEmail = $appointmentCreationEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getAppointmentCreationSms()
    {
        return $this->appointmentCreationSms;
    }

    /**
     * @param string $appointmentCreationSms
     * @return CommunicationsSettings
     */
    public function setAppointmentCreationSms($appointmentCreationSms)
    {
        $this->appointmentCreationSms = $appointmentCreationSms;
        return $this;
    }

    /**
     * @return string
     */
    public function getAppointmentReminderEmail()
    {
        return $this->appointmentReminderEmail;
    }

    /**
     * @param string $appointmentReminderEmail
     * @return CommunicationsSettings
     */
    public function setAppointmentReminderEmail($appointmentReminderEmail)
    {
        $this->appointmentReminderEmail = $appointmentReminderEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getRecallEmail()
    {
        return $this->recallEmail;
    }

    /**
     * @param string $recallEmail
     * @return CommunicationsSettings
     */
    public function setRecallEmail($recallEmail)
    {
        $this->recallEmail = $recallEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getRecallSms()
    {
        return $this->recallSms;
    }

    /**
     * @param string $recallSms
     * @return CommunicationsSettings
     */
    public function setRecallSms($recallSms)
    {
        $this->recallSms = $recallSms;
        return $this;
    }

    /**
     * @return string
     */
    public function getAppointmentReminderSms()
    {
        return $this->appointmentReminderSms;
    }

    /**
     * @param string $appointmentReminderSms
     * @return CommunicationsSettings
     */
    public function setAppointmentReminderSms($appointmentReminderSms)
    {
        $this->appointmentReminderSms = $appointmentReminderSms;
        return $this;
    }

    /**
     * @return string
     */
    public function getWhenRemainderEmailSentDay()
    {
        return $this->whenRemainderEmailSentDay;
    }

    /**
     * @param string $whenRemainderEmailSentDay
     */
    public function setWhenRemainderEmailSentDay($whenRemainderEmailSentDay)
    {
        $this->whenRemainderEmailSentDay = $whenRemainderEmailSentDay;
    }

    /**
     * @return string
     */
    public function getWhenRemainderEmailSentTime()
    {
        return $this->whenRemainderEmailSentTime;
    }

    /**
     * @param string $whenRemainderEmailSentTime
     */
    public function setWhenRemainderEmailSentTime($whenRemainderEmailSentTime)
    {
        $this->whenRemainderEmailSentTime = $whenRemainderEmailSentTime;
    }

    /**
     * @return string
     */
    public function getWhenRemainderSmsSentDay()
    {
        return $this->whenRemainderSmsSentDay;
    }

    /**
     * @param string $whenRemainderSmsSentDay
     */
    public function setWhenRemainderSmsSentDay($whenRemainderSmsSentDay)
    {
        $this->whenRemainderSmsSentDay = $whenRemainderSmsSentDay;
    }

    /**
     * @return string
     */
    public function getWhenRemainderSmsSentTime()
    {
        return $this->whenRemainderSmsSentTime;
    }

    /**
     * @param string $whenRemainderSmsSentTime
     */
    public function setWhenRemainderSmsSentTime($whenRemainderSmsSentTime)
    {
        $this->whenRemainderSmsSentTime = $whenRemainderSmsSentTime;
    }

    /**
     * @return string
     */
    public function getRecallEmailSubject()
    {
        return $this->recallEmailSubject;
    }

    /**
     * @param string $recallEmailSubject
     * @return CommunicationsSettings
     */
    public function setRecallEmailSubject($recallEmailSubject)
    {
        $this->recallEmailSubject = $recallEmailSubject;
        return $this;
    }

    /**
     * @return string
     */
    public function getNoShowSubject()
    {
        return $this->noShowSubject;
    }

    /**
     * @param string $noShowSubject
     * @return CommunicationsSettings
     */
    public function setNoShowSubject($noShowSubject)
    {
        $this->noShowSubject = $noShowSubject;
        return $this;
    }

    /**
     * @return string
     */
    public function getNoShowEmail()
    {
        return $this->noShowEmail;
    }

    /**
     * @param string $noShowEmail
     * @return CommunicationsSettings
     */
    public function setNoShowEmail($noShowEmail)
    {
        $this->noShowEmail = $noShowEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getNewPatientFirstAppointmentEmail()
    {
        return $this->newPatientFirstAppointmentEmail;
    }

    /**
     * @param string $newPatientFirstAppointmentEmail
     * @return CommunicationsSettings
     */
    public function setNewPatientFirstAppointmentEmail($newPatientFirstAppointmentEmail)
    {
        $this->newPatientFirstAppointmentEmail = $newPatientFirstAppointmentEmail;
        return $this;
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
     * @return CommunicationsSettings
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
     * @return CommunicationsSettings
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
     * @return CommunicationsSettings
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
     * @return CommunicationsSettings
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
