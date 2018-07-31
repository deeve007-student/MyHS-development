<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 17:57
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\UnavailableBlock;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\Event\OnFlushEventArgs;

/**
 * Class EventMirrorListener
 */
class EventMirrorListener
{

    use RecomputeChangesTrait;

    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {

            if ($entity instanceof UnavailableBlock) {
                $resource = $entity->getResource();
                if (false == $resource->getDefault()) {
                    $entity->setIsMirror(false);
                    $this->recomputeEntityChangeSet($entity, $em);
                }
            }
        }
    }

}
