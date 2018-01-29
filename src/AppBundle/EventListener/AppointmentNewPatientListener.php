<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 27.06.2017
 * Time: 11:59
 */

namespace AppBundle\EventListener;

use AppBundle\Event\AppointmentEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\EntityManager;

class AppointmentNewPatientListener
{

    use RecomputeChangesTrait;

    /** @var EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onAppointmentCreated(AppointmentEvent $event)
    {
        $entity = $event->getAppointment();
        $patient = $entity->getPatient();

        if (!$patient->getId()) {
            $entity->setNewPatient(true);
            $this->recomputeEntityChangeSet($entity, $this->entityManager);
        }

    }

}