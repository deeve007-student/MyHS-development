<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 17:57
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Reschedule;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventRescheduleListener
{

    use RecomputeChangesTrait;

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {

            if ($entity instanceof Appointment) {
                $changeSet = $uow->getEntityChangeSet($entity);
                if (array_key_exists('start', $changeSet)) {
                    $reschedule = new Reschedule();
                    $reschedule->setAppointment($entity)
                        ->setStart($changeSet['start'][0])
                        ->setEnd($changeSet['end'][0]);

                    $em->persist($reschedule);
                    $this->computeEntityChangeSet($reschedule, $em);
                }
            }
        }
    }

}
