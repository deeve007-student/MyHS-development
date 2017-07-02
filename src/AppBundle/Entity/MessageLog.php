<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 03.07.2017
 * Time: 0:41
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="message_log")
 * @ORM\HasLifecycleCallbacks()
 */
class MessageLog
{

    use OwnerFieldTrait;
    use CreatedUpdatedTrait;

    const TYPE_SMS = 'sms';
    const TYPE_SMS_ICON = '<i class="fa fa-envelope-o" aria-hidden="true"></i>';
    const TYPE_EMAIL = 'email';
    const TYPE_EMAIL_ICON = '<i class="fa fa-mobile" aria-hidden="true"></i>';
    const TYPE_CALL = 'email';
    const TYPE_CALL_ICON = '<i class="fa fa-phone" aria-hidden="true"></i>';

    const CATEGORY_INVOICE_SENT = 'invoice_sent';
    const CATEGORY_APPOINTMENT_CREATED = 'appointment_created';

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
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $category;

    /**
     * @var Patient
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Patient")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id", nullable=false)
     */
    protected $patient;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=false)
     */
    protected $date;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $routeData;

    public function __toString()
    {
        return $this->getCategory();
    }

    public function __construct()
    {
        return $this->setDate(new \DateTime());
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
     * @param string $type
     * @return MessageLog
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set routeData
     *
     * @param array $routeData
     * @return MessageLog
     */
    public function setRouteData(array $routeData)
    {
        $this->routeData = base64_encode(json_encode($routeData));

        return $this;
    }

    /**
     * Get routeData
     *
     * @return array
     */
    public function getRouteData()
    {
        return json_decode(base64_decode($this->routeData));
    }

    /**
     * Set name
     *
     * @param string $category
     * @return MessageLog
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return MessageLog
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
     * Set patient
     *
     * @param \AppBundle\Entity\Patient $patient
     * @return MessageLog
     */
    public function setPatient(\AppBundle\Entity\Patient $patient)
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
}
