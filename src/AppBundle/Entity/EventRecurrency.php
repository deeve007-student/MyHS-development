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
use AppBundle\Form\Type\EventRecurrencyType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Recurr\Rule;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @ORM\Entity
 * @ORM\Table(name="event_recurrency")
 * @ORM\HasLifecycleCallbacks()
 */
class EventRecurrency
{

    use CreatedUpdatedTrait;
    use OwnerFieldTrait;

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
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $customType = self::DAILY;

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
    protected $dateEnd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    protected $lastEventDate;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $weekdays;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $every = 1;

    /**
     * EventRecurrency constructor.
     */
    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->weekdays = json_encode([]);
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
    public function setCount($count = null)
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
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * @param \DateTime $dateEnd
     * @return EventRecurrency
     */
    public function setDateEnd($dateEnd = null)
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }

    /**
     * @return array
     */
    public function getWeekdays()
    {
        return json_decode($this->weekdays);
    }

    /**
     * @param array $weekdays
     * @return EventRecurrency
     */
    public function setWeekdays(array $weekdays)
    {
        $this->weekdays = json_encode($weekdays);
        return $this;
    }

    /**
     * @return int
     */
    public function getEvery()
    {
        return $this->every;
    }

    /**
     * @param int $every
     */
    public function setEvery($every)
    {
        $this->every = $every;
    }

    /**
     * @return string
     */
    public function getCustomType()
    {
        return $this->customType;
    }

    /**
     * @param string $customType
     * @return EventRecurrency
     */
    public function setCustomType($customType)
    {
        $this->customType = $customType;
        return $this;
    }

    /**
     * @return Event
     */
    public function getFirstEvent()
    {
        return $this->getEvents()->first();
    }

    /**
     * @return Event
     */
    public function getLastEvent()
    {
        return $this->getEvents()->last();
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
            case self::CUSTOM:

                $this->setRuleSettings($this->getCustomType(), $date, $rule);
                $rule->setInterval($this->getEvery());

                if (!is_null($this->getCount())) {
                    $rule->setCount($this->getCount() * $this->getEvery() - $this->getEvents()->count() + 1);
                }

                if (!is_null($this->getDateEnd())) {
                    $limitDate = $this->getDateEnd();
                }

                if ($this->getDateEnd()) {
                    $limitDate = $this->getDateEnd();
                }

                break;
            default:
                $limitDate = $this->setRuleSettings($this->getType(), $date, $rule);
        }

        if (!is_null($limitDate)) {
            $rule->setUntil($limitDate);
        }

        return $rule;
    }

    /**
     * @param $type
     * @param \DateTime $date
     * @param Rule $rule
     * @return \DateTime
     * @throws \Recurr\Exception\InvalidArgument
     * @throws \Exception
     */
    protected function setRuleSettings($type, \DateTime $date, Rule $rule)
    {
        $limitDate = null;

        switch ($type) {
            case self::NO_REPEAT:
                $rule->setFreq('DAILY');
                $rule->setCount(1 - $this->getEvents()->count());
                break;
            case self::DAILY:
                $rule->setFreq('DAILY');
                $limitDate = (clone $date)->modify('+30 days');
                break;
            case self::WEEKLY:
                $rule->setFreq('WEEKLY');
                if (count($this->getWeekdays()) > 0) {
                    $rule->setByDay($this->getWeekdays());
                }
                $limitDate = (clone $date)->modify('+4 weeks');
                break;
            case self::WEEKDAY:
                $rule->setFreq('WEEKLY');
                $rule->setByDay(['MO', 'TU', 'WE', 'TH', 'FR']);
                if (count($this->getWeekdays()) > 0) {
                    $rule->setByDay($this->getWeekdays());
                }
                $limitDate = (clone $date)->modify('+60 days');
                break;
            case self::MONTHLY:
                $rule->setFreq('MONTHLY');
                $weekdays = ['SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA'];
                $weekday = $weekdays[$date->format('w')];
                $weekDayNumber = EventRecurrencyType::getWeekOfMonthNumber($date);
                $rule->setByDay([$weekDayNumber . $weekday]);
                $limitDate = (clone $date)->modify('+90 days');
                break;
            case self::ANNUALLY:
                $rule->setFreq('YEARLY');
                $limitDate = (clone $date)->modify('+6 years');
                break;
            default:
                throw new \Exception('Undefined event recurrency: ' . $this->getType());
        }

        return $limitDate;
    }


}