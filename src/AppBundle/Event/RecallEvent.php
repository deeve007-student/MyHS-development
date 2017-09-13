<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 18:27
 */

namespace AppBundle\Event;

use AppBundle\Entity\Recall;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\Event;

class RecallEvent extends Event
{

    const RECALL_CREATED = 'recall.created';
    const RECALL_UPDATED = 'recall.updated';

    /** @var Recall */
    protected $recall;

    /** @var array */
    protected $changeSet;

    /** @var EntityManager */
    protected $entityManager;

    public function __construct(Recall $recall)
    {
        $this->recall = $recall;
        $this->setChangeSet(array());
    }

    public function getRecall()
    {
        return $this->recall;
    }

    public function setChangeSet(array $changeSet)
    {
        $this->changeSet = $changeSet;
        return $this;
    }

    public function getChangeSet()
    {
        return $this->changeSet;
    }

    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

}
