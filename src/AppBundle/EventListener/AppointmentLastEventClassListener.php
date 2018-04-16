<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 27.06.2017
 * Time: 11:59
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Invoice;
use AppBundle\Event\AppointmentEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\EntityManager;
use Symfony\Component\VarDumper\VarDumper;

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
            $lastEventClass = Appointment::FUTURE_BOOKING_CLASS;
            $this->processClasses($entity, $lastEventClass);
        }
    }

    public function onAppointmentUpdated(AppointmentEvent $event)
    {
        $entity = $event->getAppointment();

        $lastEventClass = null;

        if (isset($event->getChangeSet()['patientArrived'])) {
            if ($event->getChangeSet()['patientArrived'][1] == true) {
                $lastEventClass = Appointment::PATIENT_ARRIVED_CLASS;
            } else {
                $lastEventClass = $entity->getLastEventPrevClass();
            }
        }

        /*
        if (isset($event->getChangeSet()['invoice'])) {
            //if ($event->getChangeSet()['invoice'][1] && $event->getChangeSet()['invoice'][1]->getStatus() == Invoice::STATUS_PAID) {
            if ($event->getChangeSet()['invoice'][1]) {
                $lastEventClass = Appointment::INVOICE_PAID_CLASS;
            }
            if (!$event->getChangeSet()['invoice'][1] && $entity->getLastEventClass() == Appointment::INVOICE_PAID_CLASS) {
                $lastEventClass = $entity->getLastEventPrevClass();
            }
        }
        */

        if (isset($event->getChangeSet()['invoicePaid'])) {
            $lastEventClass = Appointment::INVOICE_PAID_CLASS;
        }

        if (isset($event->getChangeSet()['start'])) {
            if ($this->checkIfFutureBooking($entity)) {
                $lastEventClass = Appointment::FUTURE_BOOKING_CLASS;
            } else {
                $lastEventClass = $entity->getLastEventPrevClass();
            }
        }

        if ($lastEventClass) {
        $this->processClasses($entity, $lastEventClass);
        }
    }

    protected function processClasses(Appointment $appointment, $eventClass)
    {
        if ($appointment->getLastEventClass() !== Appointment::PATIENT_ARRIVED_CLASS) {
            $appointment->setLastEventPrevClass($appointment->getLastEventClass());
        }

        $appointment->setLastEventClass($eventClass);

        $this->recomputeEntityChangeSet($appointment, $this->entityManager);
    }

}
