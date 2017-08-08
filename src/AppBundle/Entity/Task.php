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
 * @ORM\Table(name="task")
 * @ORM\HasLifecycleCallbacks()
 */
class Task
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
     * @var \DateTime
     * @ORM\Column(type="date", nullable=false)
     */
    protected $date;

    /**
     * @var boolean
     * @ORM\Column(type="string", nullable=true)
     */
    protected $completed;

    /**
     * @var RecurringTask
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RecurringTask", inversedBy="tasks", cascade={"persist"})
     * @ORM\JoinColumn(name="recurring_task_id", referencedColumnName="id", nullable=false)
     */
    protected $recurringTask;

    public function __toString()
    {
        return (string)$this->getRecurringTask();
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
     * @param \DateTime $date
     * @return Task
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
     * Set completed
     *
     * @param string $completed
     * @return Task
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Get completed
     *
     * @return string 
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set recurringTask
     *
     * @param \AppBundle\Entity\RecurringTask $recurringTask
     * @return Task
     */
    public function setRecurringTask(\AppBundle\Entity\RecurringTask $recurringTask = null)
    {
        $this->recurringTask = $recurringTask;

        return $this;
    }

    /**
     * Get recurringTask
     *
     * @return \AppBundle\Entity\RecurringTask 
     */
    public function getRecurringTask()
    {
        return $this->recurringTask;
    }
}
