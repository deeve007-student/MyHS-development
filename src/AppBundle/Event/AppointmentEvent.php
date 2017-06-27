<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 18:27
 */

namespace AppBundle\Event;

use AppBundle\Entity\Appointment;
use Symfony\Component\EventDispatcher\Event;

class AppointmentEvent extends Event
{

    const APPOINTMENT_CREATED = 'appointment.created';
    const APPOINTMENT_UPDATED = 'appointment.updated';

    /** @var Appointment */
    protected $appointment;

    /** @var array */
    protected $changeSet;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
        $this->setChangeSet(array());
    }

    public function getAppointment()
    {
        return $this->appointment;
    }

    public function setChangeSet(array $changeSet)
    {
        $this->changeSet = $changeSet;
        return $this;
    }

    public function getChangeSet()
    {
        return $this->changeSet;
    }

}
