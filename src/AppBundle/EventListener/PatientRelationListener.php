<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.11.2016
 * Time: 19:39
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\PatientRelationship;
use AppBundle\Entity\RelatedPatient;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;

class PatientRelationListener
{

    use RecomputeChangesTrait;

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $relation) {
            if ($relation instanceof RelatedPatient) {

                if (!$relation->getMainPatient()->getId() || !$this->getReverseRelation($relation, $em)) {
                    $this->createReverseRelation($relation, $em);
                }

            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $relation) {
            if ($relation instanceof RelatedPatient) {

                $changeset = $uow->getEntityChangeSet($relation);

                if (array_key_exists('patient', $changeset)) {
                    if ($reverseRelation = $this->getReverseRelation($relation, $em, $changeset['patient'][0])) {
                        $reverseRelation->setMainPatient($changeset['patient'][1]);

                        if (array_key_exists('patientRelationship', $changeset)) {
                            $this->updateReverseRelationType($relation, $reverseRelation, $em);
                        }

                        $this->computeEntityChangeSet($reverseRelation, $em);
                    } else {
                        $this->createReverseRelation($relation, $em);
                    }
                }

                if (array_key_exists('patientRelationship', $changeset)) {
                    $this->updateReverseRelationType($relation, $reverseRelation, $em);
                }

            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $relation) {
            if ($relation instanceof RelatedPatient) {

                if ($reverseRelation = $this->getReverseRelation($relation, $em)) {
                    $em->remove($reverseRelation);
                    $this->computeEntityChangeSet($reverseRelation, $em);
                }

            }
        }

    }

    /**
     * @param RelatedPatient $relation
     * @param EntityManager $em
     * @return RelatedPatient
     */
    protected function createReverseRelation(RelatedPatient $relation, EntityManager $em)
    {
        $reverse = new RelatedPatient();

        $reverse->setMainPatient($relation->getPatient())
            ->setPatient($relation->getMainPatient())
            ->setPatientRelationship($relation->getPatientRelationship())
            ->setPatientRelationship($this->getReverseRelationType($relation->getPatientRelationship(), $em));

        $em->persist($reverse);
        $this->computeEntityChangeSet($reverse, $em);

        return $reverse;
    }

    /**
     * @param RelatedPatient $relation
     * @param RelatedPatient $reverseRelation
     * @param EntityManager $em
     */
    protected function updateReverseRelationType(
        RelatedPatient $relation,
        RelatedPatient $reverseRelation,
        EntityManager $em
    ) {
        if ($reverseRelationType = $this->getReverseRelationType(
            $relation->getPatientRelationship(),
            $em
        )
        ) {
            $reverseRelation->setPatientRelationship($reverseRelationType);
            $this->recomputeEntityChangeSet($reverseRelation, $em);
        }
    }

    /**
     * @param PatientRelationship $patientRelationship
     * @param EntityManager $em
     * @param null $oldPatientRelationship
     * @return PatientRelationship|null|object
     */
    protected function getReverseRelationType(
        PatientRelationship $patientRelationship,
        EntityManager $em,
        $oldPatientRelationship = null
    ) {
        return $em->getRepository('AppBundle:PatientRelationship')->findOneBy(
            array('name' => $oldPatientRelationship ? $oldPatientRelationship : $patientRelationship->getReverseName())
        );
    }

    /**
     * @param RelatedPatient $relation
     * @param EntityManager $em
     * @param null $oldPatient
     * @return RelatedPatient|null
     */
    protected function getReverseRelation(RelatedPatient $relation, EntityManager $em, $oldPatient = null)
    {
        $qb = $em->getRepository('AppBundle:RelatedPatient')->createQueryBuilder('r');
        $qb->where(
            $qb->expr()->andX(
                $qb->expr()->eq('r.mainPatient', ':mainPatient'),
                $qb->expr()->eq('r.patient', ':patient')
            )
        )->setParameters(
            array(
                'mainPatient' => $oldPatient ? $oldPatient : $relation->getPatient(),
                'patient' => $relation->getMainPatient(),
            )
        );

        return $qb->getQuery()->getOneOrNullResult();
    }

}
