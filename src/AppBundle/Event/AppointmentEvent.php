<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 18:27
 */

namespace AppBundle\Event;

use AppBundle\Entity\Appointment;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\Event;

class AppointmentEvent extends Event
{

    const APPOINTMENT_CREATED = 'appointment.created';
    const APPOINTMENT_CREATED_POST_FLUSH = 'appointment.created.post_flush';
    const APPOINTMENT_UPDATED = 'appointment.updated';

    /** @var Appointment */
    protected $appointment;

    /** @var array */
    protected $changeSet;

    /** @var EntityManager */
    protected $entityManager;

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

    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

}
