<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 02.03.2017
 * Time: 15:05
 */

namespace UserBundle\Entity;

use AppBundle\Entity\CalendarSettings;
use AppBundle\Entity\CommunicationsSettings;
use AppBundle\Entity\Country;
use AppBundle\Entity\InvoiceSettings;
use AppBundle\Entity\Subscription;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{

    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $firstLogin;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $apiKey;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $businessName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $providerNumber;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $patientNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", nullable=true)
     */
    protected $country;

    /**
     * @var Subscription
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Subscription")
     * @ORM\JoinColumn(name="subscription_id", referencedColumnName="id", nullable=true)
     */
    protected $subscription;

    /**
     * @var CalendarSettings
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\CalendarSettings", mappedBy="owner")
     */
    protected $calendarSettings;

    /**
     * @var InvoiceSettings
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\InvoiceSettings", mappedBy="owner")
     */
    protected $invoiceSettings;

    /**
     * @var CommunicationsSettings
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\CommunicationsSettings", mappedBy="owner")
     */
    protected $communicationsSettings;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $timezone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $lastName;

    /**
     * @Gedmo\Slug(fields={"businessName"})
     * @ORM\Column(length=128, unique=false)
     */
    private $slug;

    public function __construct()
    {
        parent::__construct();
    }

    public function __toString()
    {
        return $this->getFullName();
    }

    public function getFullName()
    {
        return trim($this->getTitle() . ' ' . $this->getFirstName() . ' ' . $this->getLastName());
    }

    /**
     * Set apiKey
     *
     * @param string $apiKey
     * @return User
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
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
     * @return User
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
     * @return User
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
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     * @return User
     */
    public function setCountry(\AppBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \AppBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set timezone
     *
     * @param string $timezone
     * @return User
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Get timezone
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set subscription
     *
     * @param \AppBundle\Entity\Subscription $subscription
     * @return User
     */
    public function setSubscription(\AppBundle\Entity\Subscription $subscription = null)
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * Get subscription
     *
     * @return \AppBundle\Entity\Subscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * Set businessName
     *
     * @param string $businessName
     * @return User
     */
    public function setBusinessName($businessName)
    {
        $this->businessName = $businessName;

        return $this;
    }

    /**
     * Get businessName
     *
     * @return string
     */
    public function getBusinessName()
    {
        return $this->businessName;
    }

    /**
     * Set firstLogin
     *
     * @param boolean $firstLogin
     * @return User
     */
    public function setFirstLogin($firstLogin)
    {
        $this->firstLogin = $firstLogin;

        return $this;
    }

    /**
     * Get firstLogin
     *
     * @return boolean
     */
    public function getFirstLogin()
    {
        return $this->firstLogin;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return User
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set calendarSettings
     *
     * @param \AppBundle\Entity\CalendarSettings $calendarSettings
     * @return User
     */
    public function setCalendarSettings(\AppBundle\Entity\CalendarSettings $calendarSettings = null)
    {
        $this->calendarSettings = $calendarSettings;

        return $this;
    }

    /**
     * Get calendarSettings
     *
     * @return \AppBundle\Entity\CalendarSettings
     */
    public function getCalendarSettings()
    {
        return $this->calendarSettings;
    }

    /**
     * @return string
     */
    public function getProviderNumber()
    {
        return $this->providerNumber;
    }

    /**
     * @param string $providerNumber
     * @return User
     */
    public function setProviderNumber($providerNumber)
    {
        $this->providerNumber = $providerNumber;
        return $this;
    }

    /**
     * @return InvoiceSettings
     */
    public function getInvoiceSettings()
    {
        return $this->invoiceSettings;
    }

    /**
     * @param InvoiceSettings $invoiceSettings
     * @return User
     */
    public function setInvoiceSettings($invoiceSettings)
    {
        $this->invoiceSettings = $invoiceSettings;
        return $this;
    }

    /**
     * @return CommunicationsSettings
     */
    public function getCommunicationsSettings()
    {
        return $this->communicationsSettings;
    }

    /**
     * @param CommunicationsSettings $communicationsSettings
     * @return User
     */
    public function setCommunicationsSettings($communicationsSettings)
    {
        $this->communicationsSettings = $communicationsSettings;
        return $this;
    }

    /**
     * @return int
     */
    public function getPatientNumber()
    {
        return $this->patientNumber;
    }

    /**
     * @param int $patientNumber
     * @return User
     */
    public function setPatientNumber($patientNumber)
    {
        $this->patientNumber = $patientNumber;
        return $this;
    }

}
