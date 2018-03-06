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
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="communications_settings")
 * @ORM\HasLifecycleCallbacks()
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


}
