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

    /** @var Appointment */
    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function getAppointment()
    {
        return $this->appointment;
    }

}
