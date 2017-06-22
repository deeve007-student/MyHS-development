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
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="calendar_data")
 * @ORM\HasLifecycleCallbacks()
 */
class CalendarData
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
     * @return CalendarData
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
     * @return CalendarData
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
     * @return CalendarData
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
}
