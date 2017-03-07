<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 25.04.2016
 * Time: 14:52
 */

namespace AppBundle\EventListener\Traits;

use Doctrine\ORM\EntityManager;

trait RecomputeChangesTrait
{

    /**
     * Use this method to recompute changes in Doctrine event & entity listeners
     *
     * @param $object
     * @param EntityManager $entityManager
     */
    protected function recomputeEntityChangeSet($object, EntityManager $entityManager)
    {
        $metadata = $this->getMetadata($entityManager, $object);
        $entityManager->getUnitOfWork()->recomputeSingleEntityChangeSet($metadata, $object);
    }

    /**
     * Use this method to compute changes in Doctrine event & entity listeners
     *
     * @param $object
     * @param EntityManager $entityManager
     */
    protected function computeEntityChangeSet($object, EntityManager $entityManager)
    {
        $metadata = $this->getMetadata($entityManager, $object);
        $entityManager->getUnitOfWork()->computeChangeSet($metadata, $object);
    }

    private function getMetadata(EntityManager $entityManager, $object)
    {
        return $entityManager->getClassMetadata(get_class($object));
    }

}
