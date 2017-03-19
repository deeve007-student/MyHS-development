<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\Patient;
use AppBundle\Form\Type\PatientType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Patient controller.
 *
 * @Route("patient")
 */
class PatientController extends Controller
{

    /**
     * Lists all patient entities.
     *
     * @Route("/", name="patient_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $patients = $em->getRepository('AppBundle:Patient')->findAll();

        return array(
            'patients' => $patients,
        );
    }

    /**
     * Lists all patients attachments.
     *
     * @Route("/{id}/attachment", name="patient_attachment_index")
     * @Method("GET")
     * @Template("@App/Attachment/indexPatient.html.twig")
     */
    public function attachmentAction(Patient $patient)
    {
        $attachments = $patient->getAttachments();

        return array(
            'entity' => $patient,
            'attachments' => $attachments,
        );
    }

    /**
     * Creates a new patient entity.
     *
     * @Route("/new", name="patient_create")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:Patient:update.html.twig")
     */
    public function createAction(Request $request)
    {
        $patient = $this->get('app.entity_factory')->createPatient();

        return $this->update($patient);
    }

    /**
     * Finds and displays a patient entity.
     *
     * @Route("/{id}", name="patient_view")
     * @Method("GET")
     * @Template()
     */
    public function viewAction(Patient $patient)
    {
        return array(
            'entity' => $patient,
        );
    }

    /**
     * Returns patient address.
     *
     * @Route("/address/{id}", name="patient_address_view", options={"expose"=true})
     * @Method("POST")
     */
    public function viewAddressAction(Patient $patient)
    {
        return new JsonResponse(
            json_encode(
                array(
                    'address' => $patient->getAddressFull(),
                )
            )
        );
    }

    /**
     * Displays a form to edit an existing patient entity.
     *
     * @Route("/{id}/update", name="patient_update")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Patient $patient)
    {
        return $this->update($patient);
    }

    /**
     * Deletes a patient entity.
     *
     * @Route("/{id}/delete", name="patient_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Patient $patient)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($patient);
        $em->flush();

        $this->addFlash(
            'success',
            'app.patient.message.deleted'
        );

        return $this->redirectToRoute('patient_index');
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.patient.form'),
            $entity,
            'app.patient.message.created',
            'app.patient.message.updated',
            'patient_view',
            $entity->getId()
        );
    }

    /* ============================ Invoices ============================ */

    /**
     * Lists all patients invoices.
     *
     * @Route("/{id}/invoice", name="patient_invoice_index")
     * @Method("GET")
     * @Template("@App/Invoice/indexPatient.html.twig")
     */
    public function indexInvoiceAction(Patient $patient)
    {
        $invoices = $patient->getInvoices();

        return array(
            'entity' => $patient,
            'invoices' => $invoices,
        );
    }

    /**
     * Creates a new patient invoice entity.
     *
     * @Route("/{patient}/invoice/new", name="patient_invoice_create")
     * @Method({"GET", "POST"})
     * @Template("@App/Invoice/updatePatient.html.twig")
     */
    public function createInvoiceAction(Patient $patient)
    {
        $invoice = $this->get('app.entity_factory')->createInvoice($patient);

        return $this->updateInvoice($invoice);
    }

    /**
     * Finds and displays a invoice entity from patient page.
     *
     * @Route("/{patient}/invoice/{invoice}", name="patient_invoice_view")
     * @ParamConverter("patient",class="AppBundle:Patient")
     * @ParamConverter("invoice",class="AppBundle:Invoice")
     * @Method("GET")
     * @Template("@App/Invoice/viewPatient.html.twig")
     */
    public function viewInvoiceAction(Patient $patient, Invoice $invoice)
    {
        return array(
            'entity' => $invoice,
        );
    }

    /**
     * Displays a form to edit an existing invoice entity from patient page.
     *
     * @Route("/{patient}/invoice/{invoice}/update", name="patient_invoice_update")
     * @ParamConverter("patient",class="AppBundle:Patient")
     * @ParamConverter("invoice",class="AppBundle:Invoice")
     * @Method({"GET", "POST"})
     * @Template("@App/Invoice/updatePatient.html.twig")
     */
    public function updateInvoiceAction(Patient $patient, Invoice $invoice)
    {
        return $this->updateInvoice($invoice);
    }

    /**
     * Deletes a invoice entity.
     *
     * @Route("/{patient}/invoice/{invoice}/delete", name="patient_invoice_delete")
     * @ParamConverter("patient",class="AppBundle:Patient")
     * @ParamConverter("invoice",class="AppBundle:Invoice")
     * @Method({"DELETE", "GET"})
     */
    public function deleteInvoiceAction(Patient $patient, Invoice $invoice)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($invoice);
        $em->flush();

        $this->addFlash(
            'success',
            'app.invoice.message.deleted'
        );

        return $this->redirectToRoute('patient_invoice_index', array('id'=>$patient->getId()));
    }

    /**
     * Changes patient invoice status.
     *
     * @Route("/{patient}/invoice/{invoice}/status/{status}", name="patient_invoice_status_update")
     * @ParamConverter("patient",class="AppBundle:Patient")
     * @ParamConverter("invoice",class="AppBundle:Invoice")
     * @Method("GET")
     */
    public function invoiceStatusAction(Patient $patient, Invoice $invoice, $status)
    {
        if ($this->get('app.entity_factory')->updateInvoiceStatus($invoice, $status)) {
            $this->addFlash(
                'success',
                'app.invoice.message.status_changed'
            );
        } else {
            $this->addFlash(
                'danger',
                'app.invoice.message.status_no_items'
            );
        }

        return $this->redirectToRoute('patient_invoice_view', array('invoice' => $invoice->getId(), 'patient' => $invoice->getPatient()->getId()));
    }

    /**
     * Duplicates patient invoice.
     *
     * @Route("/{patient}/invoice/{invoice}/duplicate", name="patient_invoice_duplicate")
     * @ParamConverter("patient",class="AppBundle:Patient")
     * @ParamConverter("invoice",class="AppBundle:Invoice")
     * @Method("GET")
     */
    public function duplicateInvoiceAction(Patient $patient, Invoice $invoice)
    {
        $duplicate = $this->get('app.entity_factory')->duplicateInvoice($invoice);

        $this->addFlash(
            'success',
            'app.invoice.message.duplicated'
        );

        return $this->redirectToRoute(
            'patient_invoice_view',
            array('invoice' => $duplicate->getId(), 'patient' => $duplicate->getPatient()->getId())
        );
    }

    protected function updateInvoice($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.invoice.form'),
            $entity,
            'app.invoice.message.created',
            'app.invoice.message.updated',
            null,
            null,
            function (Invoice $invoice) {
                return $this->redirectToRoute(
                    'patient_invoice_view',
                    array(
                        'invoice' => $invoice->getId(),
                        'patient' => $invoice->getPatient()->getId(),
                    )
                );
            }
        );
    }
}
