<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 27.06.2017
 * Time: 11:59
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Appointment;
use AppBundle\Event\AppointmentEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\EntityManager;

class AppointmentLastEventClassListener
{

    use RecomputeChangesTrait;

    /** @var EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function checkIfFutureBooking(Appointment $appointment)
    {
        if ($appointment->getStart() > new \DateTime()) {
            return true;
        }
        return false;
    }

    public function onAppointmentCreated(AppointmentEvent $event)
    {
        $entity = $event->getAppointment();
        if ($this->checkIfFutureBooking($entity)) {
            $entity->setLastEventClass(Appointment::FUTURE_BOOKING_CLASS);
            $this->recomputeEntityChangeSet($entity, $this->entityManager);
        }
    }

    public function onAppointmentUpdated(AppointmentEvent $event)
    {
        $entity = $event->getAppointment();

        if (isset($event->getChangeSet()['patientArrived'])) {
            if ($event->getChangeSet()['patientArrived'][1] == true) {
                $entity->setLastEventClass(Appointment::PATIENT_ARRIVED_CLASS);
                $this->recomputeEntityChangeSet($entity, $this->entityManager);
            }
        }

        if (isset($event->getChangeSet()['invoice'])) {
            if ($event->getChangeSet()['invoice'][1]) {
                $entity->setLastEventClass(Appointment::INVOICE_CREATED_CLASS);
                $this->recomputeEntityChangeSet($entity, $this->entityManager);
            }
            if (!$event->getChangeSet()['invoice'][1] && $entity->getLastEventClass() == Appointment::INVOICE_CREATED_CLASS) {
                $entity->setLastEventClass(null);
                $this->recomputeEntityChangeSet($entity, $this->entityManager);
            }
        }

        if (isset($event->getChangeSet()['start'])) {
            if ($this->checkIfFutureBooking($entity)) {
                $entity->setLastEventClass(Appointment::FUTURE_BOOKING_CLASS);
                $this->recomputeEntityChangeSet($entity, $this->entityManager);
            }
        }

    }

}
