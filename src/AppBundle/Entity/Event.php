<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 06.06.2017
 * Time: 9:32
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="event")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\HasLifecycleCallbacks()
 */
class Event
{
    use OwnerFieldTrait;
    use CreatedUpdatedTrait;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isMirror;

    /**
     * @var boolean
     */
    protected $isClone = false;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * Defines - what entities in recurrency series will be affected when event is modified
     * @var string
     */
    protected $affect;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $start;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $end;

    /**
     * @var EventResource
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EventResource", inversedBy="events")
     * @ORM\JoinColumn(name="event_resource_id", referencedColumnName="id", nullable=false)
     */
    protected $resource;

    /**
     * @var EventRecurrency
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EventRecurrency", inversedBy="events", cascade={"persist"})
     * @ORM\JoinColumn(name="event_recurrency_id", referencedColumnName="id", nullable=false)
     */
    protected $recurrency;

    /**
     * @var Reschedule[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Reschedule", mappedBy="appointment")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $reschedules;

    /**
     * @var boolean
     */
    protected $skipChangesetCheck = false;

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
     * Set id
     *
     * @return Event
     */
    public function setId($id)
    {
        return $this->id = $id;
    }

    public function __construct()
    {
        $this->reschedules = new ArrayCollection();
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     * @return Event
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return Event
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function isDatesNotEqual()
    {
        if ($this->getStart() == $this->getEnd()) {
            return false;
        }
        return true;
    }

    public function isEndMoreThanStart()
    {
        if ($this->getStart() <= $this->getEnd()) {
            return true;
        }
        return false;
    }

    /**
     * Set resource
     *
     * @param \AppBundle\Entity\EventResource $resource
     * @return Event
     */
    public function setResource(\AppBundle\Entity\EventResource $resource = null)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get resource
     *
     * @return \AppBundle\Entity\EventResource
     */
    public function getResource()
    {
        return $this->resource;
    }

    public function getDurationInMinutes()
    {
        $since_start = $this->getStart()->diff($this->getEnd());
        $minutes = $since_start->days * 24 * 60;
        $minutes += $since_start->h * 60;
        $minutes += $since_start->i;
        return $minutes;
    }

    /**
     * @return Reschedule[]|ArrayCollection
     */
    public function getReschedules()
    {
        return $this->reschedules;
    }

    /**
     * @param Reschedule $reschedule
     * @return Event
     */
    public function addReschedule(Reschedule $reschedule)
    {
        $this->reschedules->add($reschedule);
        return $this;
    }

    /**
     * @param Reschedule $reschedule
     * @return Event
     */
    public function removeReschedule(Reschedule $reschedule)
    {
        $this->reschedules->removeElement($reschedule);
        return $this;
    }

    /**
     * @return bool
     */
    public function isMirror()
    {
        return $this->isMirror;
    }

    /**
     * @param bool $isMirror
     * @return Event
     */
    public function setIsMirror($isMirror)
    {
        $this->isMirror = $isMirror;
        return $this;
    }

    /**
     * @return bool
     */
    public function isClone()
    {
        return $this->isClone;
    }

    /**
     * @param bool $isClone
     * @return Event
     */
    public function setIsClone($isClone)
    {
        $this->isClone = $isClone;
        return $this;
    }

    /**
     * @return EventRecurrency
     */
    public function getRecurrency()
    {
        return $this->recurrency;
    }

    /**
     * @param EventRecurrency $recurrency
     * @return Event
     */
    public function setRecurrency($recurrency)
    {
        $this->recurrency = $recurrency;

        if (false === $recurrency->getEvents()->contains($this)) {
            $recurrency->addEvent($this);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getAffect()
    {
        return $this->affect;
    }

    /**
     * @param string $affect
     * @return Event
     */
    public function setAffect($affect)
    {
        $this->affect = $affect;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSkipChangesetCheck()
    {
        return $this->skipChangesetCheck;
    }

    /**
     * @param bool $skipChangesetCheck
     * @return Event
     */
    public function setSkipChangesetCheck($skipChangesetCheck)
    {
        $this->skipChangesetCheck = $skipChangesetCheck;
        return $this;
    }

}
