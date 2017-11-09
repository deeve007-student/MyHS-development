<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 16.06.2017
 * Time: 17:01
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="reschedule")
 * @ORM\HasLifecycleCallbacks()
 */
class Reschedule
{
    use CreatedUpdatedTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Event", inversedBy="reschedules")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=false)
     */
    protected $appointment;

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
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     * @return Reschedule
     */
    public function setStart($start)
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     * @return Reschedule
     */
    public function setEnd($end)
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @return Event
     */
    public function getAppointment()
    {
        return $this->appointment;
    }

    /**
     * @param Event $appointment
     * @return Reschedule
     */
    public function setAppointment($appointment)
    {
        $this->appointment = $appointment;
        return $this;
    }



}
