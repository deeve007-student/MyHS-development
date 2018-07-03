<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 17:57
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\TreatmentPackCredit;
use AppBundle\Event\AppointmentEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class AppointmentListener
 */
class AppointmentListener
{

    use RecomputeChangesTrait;

    /** @var EventDispatcherInterface */
    protected $dispatcher;

    /** @var Appointment */
    protected $appointment;

    /**
     * AppointmentListener constructor.
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {

            if ($entity instanceof Appointment && !$entity->getId()) {
                $event = new AppointmentEvent($entity);
                $event->setEntityManager($em);

                $this->dispatcher->dispatch(
                    AppointmentEvent::APPOINTMENT_CREATED,
                    $event
                );

                $this->appointment = $entity;
            }

        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {

            if ($entity instanceof Appointment) {
                $event = new AppointmentEvent($entity);
                $changeset = $uow->getEntityChangeSet($entity);

                $event->setEntityManager($em);
                $event->setChangeSet($changeset);

                $this->dispatcher->dispatch(
                    AppointmentEvent::APPOINTMENT_UPDATED,
                    $event
                );

                if (isset($changeset['reason']) && $changeset['reason'][1]) {
                    foreach ($entity->getAppointmentPatients() as $appointmentPatient) {
                        if ($pack = $appointmentPatient->getTreatmentPackCredit()) {
                            $this->refillTreatmentPack($pack, $em);
                        }
                    }
                }
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof Appointment) {
                foreach ($entity->getAppointmentPatients() as $appointmentPatient) {
                    if ($pack = $appointmentPatient->getTreatmentPackCredit()) {
                        $this->refillTreatmentPack($pack, $em);
                    }
                }
            }
        }

    }

    /**
     * @param PostFlushEventArgs $args
     */
    public function postFlush(PostFlushEventArgs $args)
    {
        $em = $args->getEntityManager();

        if ($this->appointment) {
            $event = new AppointmentEvent($this->appointment);
            $event->setEntityManager($em);

            $this->appointment = null;

            $this->dispatcher->dispatch(
                AppointmentEvent::APPOINTMENT_CREATED_POST_FLUSH,
                $event
            );
        }
    }

    /**
     * @param TreatmentPackCredit $pack
     * @param EntityManager $em
     */
    protected function refillTreatmentPack(TreatmentPackCredit $pack, EntityManager $em)
    {
        $pack->setAmountSpend($pack->getAmountSpend() - 1);
        $this->recomputeEntityChangeSet($pack, $em);
    }

}
