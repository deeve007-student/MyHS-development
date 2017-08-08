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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="task_recurring")
 * @ORM\HasLifecycleCallbacks()
 */
class RecurringTask
{
    use OwnerFieldTrait;
    use CreatedUpdatedTrait;

    const TYPE_STANDARD = 'standard';
    const TYPE_RECURRING = 'recurring';
    const REPEATS_ONCE = 'once';
    const REPEATS_WEEKLY = 'weekly';
    const REPEATS_MONTHLY = 'monthly';
    const REPEATS_YEARLY = 'yearly';
    const REPEAT_MONTH_DAY_OF_MONTH = 'day_of_month';
    const REPEAT_MONTH_DAY_OF_WEEK = 'day_of_week';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=false)
     */
    protected $startDate;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $text;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $repeats;

    /**
     * @var string
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $interval_week;

    /**
     * @var string
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $interval_month;

    /**
     * @var string
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $interval_year;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $repeat_days;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $repeat_month;

    /**
     * @var Task[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Task", mappedBy="recurringTask", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $tasks;

    public function __toString()
    {
        return $this->getText();
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
     * Set date
     *
     * @param \DateTime $startDate
     * @return RecurringTask
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return RecurringTask
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
     * Constructor
     */
    public function __construct()
    {
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tasks
     *
     * @param \AppBundle\Entity\Task $task
     * @return RecurringTask
     */
    public function addTask(\AppBundle\Entity\Task $task)
    {
        $this->tasks[] = $task;
        $task->setRecurringTask($this);

        return $this;
    }

    /**
     * Remove tasks
     *
     * @param \AppBundle\Entity\Task $task
     */
    public function removeTask(\AppBundle\Entity\Task $task)
    {
        $task->setRecurringTask(null);
        $this->tasks->removeElement($task);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Set interval_week
     *
     * @param integer $intervalWeek
     * @return RecurringTask
     */
    public function setIntervalWeek($intervalWeek)
    {
        $this->interval_week = $intervalWeek;

        return $this;
    }

    /**
     * Get interval_week
     *
     * @return integer 
     */
    public function getIntervalWeek()
    {
        return $this->interval_week;
    }

    /**
     * Set interval_month
     *
     * @param integer $intervalMonth
     * @return RecurringTask
     */
    public function setIntervalMonth($intervalMonth)
    {
        $this->interval_month = $intervalMonth;

        return $this;
    }

    /**
     * Get interval_month
     *
     * @return integer 
     */
    public function getIntervalMonth()
    {
        return $this->interval_month;
    }

    /**
     * Set interval_year
     *
     * @param integer $intervalYear
     * @return RecurringTask
     */
    public function setIntervalYear($intervalYear)
    {
        $this->interval_year = $intervalYear;

        return $this;
    }

    /**
     * Get interval_year
     *
     * @return integer 
     */
    public function getIntervalYear()
    {
        return $this->interval_year;
    }

    /**
     * Set repeat_days
     *
     * @param array $repeatDays
     * @return RecurringTask
     */
    public function setRepeatDays(array $repeatDays)
    {
        $this->repeat_days = json_encode($repeatDays);

        return $this;
    }

    /**
     * Get repeat_days
     *
     * @return array
     */
    public function getRepeatDays()
    {
        return json_decode($this->repeat_days);
    }

    /**
     * Set repeat_month
     *
     * @param string $repeatMonth
     * @return RecurringTask
     */
    public function setRepeatMonth($repeatMonth)
    {
        $this->repeat_month = $repeatMonth;

        return $this;
    }

    /**
     * Get repeat_month
     *
     * @return string 
     */
    public function getRepeatMonth()
    {
        return $this->repeat_month;
    }

    /**
     * Set repeats
     *
     * @param string $repeats
     * @return RecurringTask
     */
    public function setRepeats($repeats)
    {
        $this->repeats = $repeats;

        return $this;
    }

    /**
     * Get repeats
     *
     * @return string 
     */
    public function getRepeats()
    {
        return $this->repeats;
    }
}
