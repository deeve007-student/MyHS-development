<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.06.2017
 * Time: 15:18
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="event_resource")
 * @ORM\HasLifecycleCallbacks()
 */
class EventResource
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
     * @ORM\Column(type="string", nullable=false)
     */
    protected $name;

    /**
     * @var Resource
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Event", mappedBy="resource", cascade={"remove"}, orphanRemoval=true)
     */
    protected $events;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $position;

    /**
     * @var CalendarSettings
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CalendarSettings", inversedBy="resources")
     * @ORM\JoinColumn(name="calendar_settings_id", referencedColumnName="id", nullable=false)
     */
    protected $calendarSettings;

    /**
     * @var boolean
     *
     * @ORM\Column(name="default_resource", type="boolean", nullable=true)
     */
    protected $default;

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
     * @return EventResource
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
     * Constructor
     */
    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add events
     *
     * @param \AppBundle\Entity\Event $events
     * @return EventResource
     */
    public function addEvent(\AppBundle\Entity\Event $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \AppBundle\Entity\Event $events
     */
    public function removeEvent(\AppBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return EventResource
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set calendarSettings
     *
     * @param \AppBundle\Entity\CalendarSettings $calendarSettings
     * @return EventResource
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
     * Set default
     *
     * @param boolean $default
     * @return EventResource
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Get default
     *
     * @return boolean 
     */
    public function getDefault()
    {
        return $this->default;
    }
}
