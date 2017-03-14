<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 11:20
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\Patient;
use AppBundle\Entity\PatientAlert;
use AppBundle\Entity\Product;
use AppBundle\Entity\TreatmentNoteTemplate;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use UserBundle\Entity\User;

class EntityFactory
{

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  TokenStorage */
    protected $tokenStorage;

    public function __construct(EntityManager $entityManager, TokenStorage $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
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

    public function createProduct()
    {
        $product = new Product();

        return $product;
    }

    public function createInvoice(Patient $patient = null, User $user = null)
    {
        if (!$user) {
            $user = $this->tokenStorage->getToken()->getUser();
        }

        $invNumber = $user->getInvoiceCounter() + 1;
        $invNumberFormatted = str_pad($invNumber, 5, '0', STR_PAD_LEFT);
        $user->setInvoiceCounter($invNumber);

        $invoice = new Invoice();
        $invoice->setName($invNumberFormatted)
            ->setStatus(Invoice::STATUS_DRAFT);

        if ($patient) {
            $invoice->setPatient($patient);
        }

        return $invoice;
    }

    public function createTreatmentNoteTemplate()
    {
        $patient = new TreatmentNoteTemplate();

        return $patient;
    }

}
