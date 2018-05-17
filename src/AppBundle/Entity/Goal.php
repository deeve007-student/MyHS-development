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
 * @ORM\Table(name="goal")
 * @ORM\HasLifecycleCallbacks()
 */
class Goal
{
    use OwnerFieldTrait;
    use CreatedUpdatedTrait;

    const WHEN_MONTH = 'month';
    const WHEN_QUARTER = 'quarter';
    const WHEN_YEAR = 'year';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $goal;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $actionStep;

    /**
     * @var string
     * @ORM\Column(name="when_value", type="string", length=255, nullable=false)
     */
    protected $when;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $completed = false;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->goal;
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
     * @return string
     */
    public function getGoal()
    {
        return $this->goal;
    }

    /**
     * @param string $goal
     * @return Goal
     */
    public function setGoal($goal)
    {
        $this->goal = $goal;
        return $this;
    }

    /**
     * @return string
     */
    public function getActionStep()
    {
        return $this->actionStep;
    }

    /**
     * @param string $actionStep
     * @return Goal
     */
    public function setActionStep($actionStep)
    {
        $this->actionStep = $actionStep;
        return $this;
    }

    /**
     * @return string
     */
    public function getWhen()
    {
        return $this->when;
    }

    /**
     * @param string $when
     * @return Goal
     */
    public function setWhen($when)
    {
        $this->when = $when;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCompleted()
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     * @return Goal
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
        return $this;
    }

}
