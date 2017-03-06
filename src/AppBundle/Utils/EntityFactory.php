<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 11:20
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Patient;
use AppBundle\Entity\PatientAlert;
use AppBundle\Entity\TreatmentNoteTemplate;
use Doctrine\ORM\EntityManager;

class EntityFactory
{

    /** @var  EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createPatient()
    {
        $patient = new Patient();
        $patient->setAutoRemindSMS(true)
            ->setAutoRemindEmail(true)
            ->setBookingConfirmationEmail(true);

        return $patient;
    }

    public function createPatientAlert(Patient $patient)
    {
        $patientAlert = new PatientAlert();
        $patientAlert->setPatient($patient);

        return $patientAlert;
    }

    public function createTreatmentNoteTemplate()
    {
        $patient = new TreatmentNoteTemplate();

        return $patient;
    }

}
