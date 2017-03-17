<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Patient;
use AppBundle\Form\Type\PatientType;
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
     * Lists all patients invoices.
     *
     * @Route("/{id}/invoice", name="patient_invoice_index")
     * @Method("GET")
     * @Template("@App/Invoice/patientIndex.html.twig")
     */
    public function invoicesAction(Patient $patient)
    {
        $invoices = $patient->getInvoices();
        return array(
            'entity' => $patient,
            'invoices' => $invoices,
        );
    }

    /**
     * Lists all patients attachments.
     *
     * @Route("/{id}/attachment", name="patient_attachment_index")
     * @Method("GET")
     * @Template("@App/Attachment/patientIndex.html.twig")
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
}
