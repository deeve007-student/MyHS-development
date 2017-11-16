<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 22.06.2017
 * Time: 16:21
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="calendar_settings")
 * @ORM\HasLifecycleCallbacks()
 */
class CalendarSettings
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
    protected $workDayStart;

    /**
     * @var EventResource
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\EventResource", mappedBy="calendarSettings", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $resources;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $workDayEnd;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $timeInterval;

    public function __toString()
    {
        return (string)$this->getId();
    }

    public function __construct()
    {
        $this->resources = new ArrayCollection();
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
     * Set workDayStart
     *
     * @param string $workDayStart
     * @return CalendarSettings
     */
    public function setWorkDayStart($workDayStart)
    {
        $this->workDayStart = $workDayStart;

        return $this;
    }

    /**
     * Get workDayStart
     *
     * @return string
     */
    public function getWorkDayStart()
    {
        return $this->workDayStart;
    }

    /**
     * Set workDayEnd
     *
     * @param string $workDayEnd
     * @return CalendarSettings
     */
    public function setWorkDayEnd($workDayEnd)
    {
        $this->workDayEnd = $workDayEnd;

        return $this;
    }

    /**
     * Get workDayEnd
     *
     * @return string
     */
    public function getWorkDayEnd()
    {
        return $this->workDayEnd;
    }

    /**
     * Set timeInterval
     *
     * @param string $timeInterval
     * @return CalendarSettings
     */
    public function setTimeInterval($timeInterval)
    {
        $this->timeInterval = $timeInterval;

        return $this;
    }

    /**
     * Get timeInterval
     *
     * @return string
     */
    public function getTimeInterval()
    {
        return $this->timeInterval;
    }


    /**
     * Add resource
     *
     * @param \AppBundle\Entity\EventResource $resource
     * @return CalendarSettings
     */
    public function addResource(\AppBundle\Entity\EventResource $resource)
    {
        $this->resources[] = $resource;
        $resource->setCalendarSettings($this);

        return $this;
    }

    /**
     * Remove resource
     *
     * @param \AppBundle\Entity\EventResource $resource
     */
    public function removeResource(\AppBundle\Entity\EventResource $resource)
    {
        $this->resources->removeElement($resource);
        $resource->setCalendarSettings(null);
    }

    /**
     * Get resources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResources()
    {
        return $this->resources;
    }
}
