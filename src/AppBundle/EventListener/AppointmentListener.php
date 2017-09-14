<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 17:57
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Appointment;
use AppBundle\Event\AppointmentEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AppointmentListener
{

    use RecomputeChangesTrait;

    /** @var EventDispatcherInterface */
    protected $dispatcher;

    /** @var Appointment */
    protected $appointment;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

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
                $event->setChangeSet($uow->getEntityChangeSet($entity));

                $this->dispatcher->dispatch(
                    AppointmentEvent::APPOINTMENT_UPDATED,
                    $event
                );
            }

        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

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

}
