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

        $this->onAppointmentCreated($event);
        $this->recomputeEntityChangeSet($entity, $this->entityManager);

        $lastEventClass = null;

        if (isset($event->getChangeSet()['patientArrived'])) {
            if ($event->getChangeSet()['patientArrived'][1] == true) {
                $lastEventClass = Appointment::PATIENT_ARRIVED_CLASS;
            } else {
                $lastEventClass = null;
            }
        }

        if (isset($event->getChangeSet()['invoice'])) {
            if ($event->getChangeSet()['invoice'][1]) {
                $lastEventClass = Appointment::INVOICE_CREATED_CLASS;
            }
            if (!$event->getChangeSet()['invoice'][1] && $entity->getLastEventClass() == Appointment::INVOICE_CREATED_CLASS) {
                $lastEventClass = null;
            }
        }

        if (isset($event->getChangeSet()['start'])) {
            if ($this->checkIfFutureBooking($entity)) {
                $lastEventClass = Appointment::FUTURE_BOOKING_CLASS;
            }
        }

        $entity->setLastEventClass($lastEventClass);
        $this->recomputeEntityChangeSet($entity, $this->entityManager);

    }

}
