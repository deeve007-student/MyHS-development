<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 17:57
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\UnavailableBlock;
use AppBundle\Event\AppointmentEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use UserBundle\Entity\User;

class UnavailableBlockListener
{

    use RecomputeChangesTrait;

    protected $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        /*
        foreach (array_merge($uow->getScheduledEntityInsertions(), $uow->getScheduledEntityUpdates()) as $entity) {
            if ($entity instanceof UnavailableBlock) {
                $user = $this->tokenStorage->getToken()->getUser();
                $resources = $user->getCalendarSettings()->getResources()->toArray();
                $firstResource = array_shift($resources);
                $entity->setResource($firstResource);
                $this->recomputeEntityChangeSet($entity, $args->getEntityManager());
            }
        }
        */
    }

}
