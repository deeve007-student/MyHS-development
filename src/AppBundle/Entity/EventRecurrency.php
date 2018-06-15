<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 10.06.2018
 * Time: 11:41
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use AppBundle\Event\RecallEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Recurr\Rule;

/**
 * @ORM\Entity
 * @ORM\Table(name="event_recurrency")
 * @ORM\HasLifecycleCallbacks()
 */
class EventRecurrency
{

    use CreatedUpdatedTrait;

    const NO_REPEAT = 'no_repeat';
    const DAILY = 'daily';
    const WEEKLY = 'weekly';
    const MONTHLY = 'monthly';
    const ANNUALLY = 'annually';
    const WEEKDAY = 'every_weekday';
    const CUSTOM = 'custom';

    const AFFECT_THIS = 'this';
    const AFFECT_THIS_AND_FOLLOWING = 'this_and_following';
    const AFFECT_ALL = 'all';

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
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $count;

    /**
     * @var Event[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Event", mappedBy="recurrency", cascade={"remove", "persist"}, orphanRemoval=true)
     */
    protected $events;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    protected $dateStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    protected $lastEventDate;

    /**
     * EventRecurrency constructor.
     */
    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return EventRecurrency
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Event[]|ArrayCollection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param Event $event
     * @return EventRecurrency
     */
    public function addEvent($event)
    {
        $this->events->add($event);
        $event->setRecurrency($this);
        return $this;
    }

    /**
     * @param Event $event
     * @return EventRecurrency
     */
    public function removeEvent($event)
    {
        $this->events->removeElement($event);
        $event->setRecurrency(null);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * @param \DateTime $dateStart
     * @return EventRecurrency
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
        return $this;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     * @return EventRecurrency
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastEventDate()
    {
        return $this->lastEventDate;
    }

    /**
     * @param \DateTime $lastEventDate
     * @return EventRecurrency
     */
    public function setLastEventDate($lastEventDate)
    {
        $this->lastEventDate = $lastEventDate;
        return $this;
    }

    /**
     * @param Event $event
     * @return $this
     */
    public function resetLastEventDate(Event $event = null)
    {
        if (is_null($event)) {
            $this->lastEventDate = null;
            return $this;
        }

        $this->lastEventDate = (clone $event->getStart())->modify('+1 day');
        return $this;
    }

    /**
     * @param \DateTime $date
     * @return Rule
     * @throws \Recurr\Exception\InvalidArgument
     * @throws \Recurr\Exception\InvalidRRule
     * @throws \Exception
     */
    public function getRule(\DateTime $date)
    {
        $rule = (new Rule())
            ->setStartDate($this->getDateStart())
            ->setTimezone($this->getDateStart()->getTimezone()->getName());

        $limitDate = null;

        switch ($this->getType()) {
            case self::NO_REPEAT:
                $rule->setFreq('DAILY');
                $rule->setCount(1);
                $this->setCount(1);
                break;
            case self::DAILY:
                $rule->setFreq('DAILY');
                $limitDate = (clone $date)->modify('+30 days');
                break;
            case self::WEEKLY:
                $rule->setFreq('WEEKLY');
                $limitDate = (clone $date)->modify('+10 weeks');
                break;
            case self::WEEKDAY:
                $rule->setFreq('WEEKLY');
                $limitDate = (clone $date)->modify('+60 days');
                break;
            case self::MONTHLY:
                $rule->setFreq('MONTHLY');
                $limitDate = (clone $date)->modify('+365 days');
                break;
            case self::ANNUALLY:
                $rule->setFreq('YEARLY');
                $limitDate = (clone $date)->modify('+5 years');
                break;
            default:
                throw new \Exception('Undefined event recurrency: ' . $this->getType());
        }

        if (!is_null($this->getCount()) && $this->getCount() > 0) {
            $rule->setCount($this->getCount());
        } else {
            $rule->setUntil($limitDate);
        }

        return $rule;
    }


}