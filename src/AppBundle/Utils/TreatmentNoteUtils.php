<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 07.12.17
 * Time: 17:49
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Patient;
use AppBundle\Entity\TreatmentNote;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\Translator;

class TreatmentNoteUtils
{


    /** @var  EntityManager */
    protected $em;

    /** @var  Translator */
    protected $translator;

    public function __construct(EntityManager $em, Translator $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * @param Patient $patient
     * @param TreatmentNote $tn
     * @return bool|TreatmentNote
     */
    public function getLastFinalNoteByPatient(Patient $patient, TreatmentNote $tn = null)
    {
        $patientTreatmentNotesQB = $this->em->getRepository('AppBundle:TreatmentNote')->createQueryBuilder('tn');
        $patientTreatmentNotesQB->orderBy('tn.id', 'DESC')
            ->where('tn.status = :final')
            ->andWhere('tn.patient = :patient')
            ->setParameter('final', TreatmentNote::STATUS_FINAL)
            ->setParameter('patient', $patient)
            ->setMaxResults(1);

        if ($tn) {
            $patientTreatmentNotesQB->andWhere('tn.id != :id')
                ->setParameter('id', $tn->getId());
        }

        try {
            if ($lastPatientTreatmentNote = $patientTreatmentNotesQB->getQuery()->getOneOrNullResult()) {
                return $lastPatientTreatmentNote;
            }
        } catch (\Exception $exception) {
            return false;
        }

        return false;
    }

    public function getDefaultTemplate()
    {
        return $this->em->getRepository("AppBundle:TreatmentNoteTemplate")->findOneBy(
            array(
                'default' => true,
            )
        );
    }

}
