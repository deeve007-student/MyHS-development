<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 11:20
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Attachment;
use AppBundle\Entity\Concession;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoicePayment;
use AppBundle\Entity\Patient;
use AppBundle\Entity\PatientAlert;
use AppBundle\Entity\Product;
use AppBundle\Entity\Recall;
use AppBundle\Entity\Task;
use AppBundle\Entity\Treatment;
use AppBundle\Entity\TreatmentNote;
use AppBundle\Entity\TreatmentNoteField;
use AppBundle\Entity\TreatmentNoteTemplate;
use AppBundle\Entity\UnavailableBlock;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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

    public function createAppointment()
    {
        return new Appointment();
    }

    public function createUnavailableBlock()
    {
        return new UnavailableBlock();
    }

    public function createConcession()
    {
        return new Concession();
    }

    public function createInvoicePayment(Invoice $invoice)
    {
        $payment = new InvoicePayment();
        $payment->setInvoice($invoice);
        $payment->setDate(new \DateTime());
        return $payment;
    }

    public function createPatient()
    {
        $patient = new Patient();
        $patient->setAutoRemindSMS(true)
            ->setAutoRemindEmail(true)
            ->setBookingConfirmationEmail(true);

        return $patient;
    }

    public function createTreatment()
    {
        $treatment = new Treatment();

        return $treatment;
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
        $product->setStockLevel(0);

        return $product;
    }

    public function createRecall(Patient $patient = null)
    {
        $recall = new Recall();

        if ($patient) {
            $recall->setPatient($patient);
        }

        return $recall;
    }

    public function createTask()
    {
        return (new Task())->setCompleted(false);
    }

    public function createInvoice(Patient $patient = null, User $user = null)
    {
        $invoice = new Invoice();
        $invoice->setName($this->generateNewInvoiceNumber($user))
            ->setStatus(Invoice::STATUS_DRAFT)
            ->setDueDate(15);

        if ($patient) {
            $invoice->setPatient($patient);
            $invoice->setPatientAddress($patient->getAddressFull());
        }

        $invoice->setDate(new \DateTime());

        return $invoice;
    }

    /**
     * @param Invoice $invoice
     * @return Invoice
     */
    public function duplicateInvoice(Invoice $invoice)
    {
        $invNumber = $this->generateNewInvoiceNumber();

        $duplicate = clone $invoice;
        $duplicate->setName($invNumber);
        $duplicate->setStatus(Invoice::STATUS_DRAFT);
        $this->entityManager->persist($duplicate);
        $this->entityManager->flush();

        return $duplicate;
    }

    /**
     * @param Invoice $invoice
     * @param $status
     * @return bool
     * @throws \Exception
     */
    public function updateInvoiceStatus(Invoice $invoice, $status)
    {
        if (!in_array($status, $invoice->getAvailableStatuses())) {
            throw new \Exception('Cant change invoice status');
        }

        if ($invoice->getItems()->count() > 0) {
            $invoice->setStatus($status);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }

    public function generateNewInvoiceNumber(User $user = null)
    {

        if (!$user) {
            $user = $this->tokenStorage->getToken()->getUser();
        }

        $invNumber = $user->getInvoiceCounter() + 1;
        $invNumberFormatted = str_pad($invNumber, 5, '0', STR_PAD_LEFT);
        $user->setInvoiceCounter($invNumber);

        return $invNumberFormatted;
    }

    public function createTreatmentNoteTemplate()
    {
        $tnTemplate = new TreatmentNoteTemplate();
        $tnTemplate->setDefault(false);

        return $tnTemplate;
    }

    public function createAttachment(Patient $patient = null)
    {
        $attachment = new Attachment();

        if ($patient) {
            $patient->addAttachment($attachment);
        }

        return $attachment;
    }

    public function createTreatmentNote(Patient $patient = null, TreatmentNoteTemplate $template)
    {
        $tn = new TreatmentNote();
        $tn->setName((string)$template);
        $tn->setTemplate($template);

        /** @var TreatmentNoteField $field */
        foreach ($template->getTreatmentNoteFields() as $field) {
            $tnField = clone $field;
            $tnField->setTemplateField($field);
            $tn->addTreatmentNoteField($tnField);
        }

        if ($patient) {
            $patient->addTreatmentNote($tn);
        }

        return $tn;
    }

}
