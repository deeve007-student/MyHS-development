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
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="no_show_message")
 * @ORM\HasLifecycleCallbacks()
 */
class NoShowMessage
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
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Appointment", inversedBy="noShowMessage")
     * @ORM\JoinColumn(name="appointment_id", referencedColumnName="id", nullable=false)
     */
    protected $appointment;

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
     * @return string
     */
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
     * Set text
     *
     * @param string $text
     * @return NoShowMessage
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
     * @return NoShowMessage
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
     * @return NoShowMessage
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
     * @return NoShowMessage
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
     * @return NoShowMessage
     */
    public function setCommunicationType($communicationType)
    {
        $this->communicationType = $communicationType;
        return $this;
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
     * @return NoShowMessage
     */
    public function setAppointment($appointment)
    {
        $this->appointment = $appointment;
        return $this;
    }

}
