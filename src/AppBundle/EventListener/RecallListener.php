<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 17:57
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Recall;
use AppBundle\Event\AppointmentEvent;
use AppBundle\Event\RecallEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RecallListener
{

    use RecomputeChangesTrait;

    /** @var EventDispatcherInterface */
    protected $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {

            if ($entity instanceof Recall && !$entity->getId()) {
                $event = new RecallEvent($entity);
                $event->setEntityManager($em);

                $this->dispatcher->dispatch(
                    RecallEvent::RECALL_CREATED,
                    $event
                );
            }

        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {

            if ($entity instanceof Recall) {
                $event = new RecallEvent($entity);
                $event->setChangeSet($uow->getEntityChangeSet($entity));

                $this->dispatcher->dispatch(
                    RecallEvent::RECALL_UPDATED,
                    $event
                );
            }

        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {

            if ($entity instanceof Recall) {
                if ($changeset = $uow->getEntityChangeSet($entity)) {
                    if (isset($changeset['completed'])) {
                        if (isset($changeset['completed'][1]) && $changeset['completed'][1]) {

                            $event = new RecallEvent($entity);
                            $event->setChangeSet($changeset);
                            $event->setEntityManager($em);

                            $this->dispatcher->dispatch(
                                RecallEvent::RECALL_COMPLETED,
                                $event
                            );

                        }
                    }
                }
            }

        }
    }

}
