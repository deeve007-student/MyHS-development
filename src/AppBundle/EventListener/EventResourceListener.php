<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 23:47
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\EventResource;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\Event\OnFlushEventArgs;

class EventResourceListener
{

    use RecomputeChangesTrait;

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {

            if ($entity instanceof EventResource) {
                if (!$entity->getDefault()) {

                    $entity->setDefault(false);
                    $this->recomputeEntityChangeSet($entity, $em);

                }
            }

        }

    }

}
