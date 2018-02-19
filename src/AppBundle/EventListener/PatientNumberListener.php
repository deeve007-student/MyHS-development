<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.11.2016
 * Time: 19:39
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Patient;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\Event\OnFlushEventArgs;

class PatientNumberListener
{

    use RecomputeChangesTrait;

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $patient) {
            if ($patient instanceof Patient) {
                $lastPatientNumber = $patient->getOwner()->getPatientNumber();
                $newPatientNumber = $lastPatientNumber + 1;
                $patient->getOwner()->setPatientNumber($newPatientNumber);
                $patient->setPatientNumber($newPatientNumber);
                $this->recomputeEntityChangeSet($patient, $em);
                $this->recomputeEntityChangeSet($patient->getOwner(), $em);
            }
        }

    }

}
