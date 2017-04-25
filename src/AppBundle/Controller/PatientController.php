<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\Patient;
use AppBundle\Utils\FilterUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
     * @Method({"GET","POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('AppBundle:Patient')
            ->createQueryBuilder('p');

        return $this->get('app.datagrid_utils')->handleDatagrid(
            $this->get('app.patient_filter.form'),
            $request,
            $qb,
            function ($qb, $filterData) {
                FilterUtils::buildTextGreedyCondition(
                    $qb,
                    array(
                        'firstName',
                        'lastName',
                        'email',
                    ),
                    $filterData['string']
                );
            },
            '@App/Patient/include/grid.html.twig'
        );
    }

    /**
     * Creates a new patient entity.
     *
     * @Route("/new", name="patient_create")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:Patient:create.html.twig")
     */
    public function createAction(Request $request)
    {
        $patient = $this->get('app.entity_factory')->createPatient();

        return $this->update($patient);
    }

    /**
     * Displays a form to edit an existing patient entity.
     *
     * @Route("/{id}/update", name="patient_update")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:Patient:update.html.twig")
     */
    public function updateAction(Request $request, Patient $patient)
    {
        return $this->update($patient);
    }

    /**
     * Finds and displays a patient entity.
     *
     * @Route("/{id}", name="patient_view")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function viewAction(Patient $patient)
    {
        /*
        return array(
            'entity' => $patient,
        );
        */
        return $this->updateNotifications($patient);
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
     * Returns patients names.
     *
     * @Route("/names/", name="patient_names", options={"expose"=true})
     * @Method({"POST"})
     */
    public function namesAction()
    {
        $patients = $this->getDoctrine()->getManager()->getRepository('AppBundle:Patient')->findAll();

        $patientNames = array_map(
            function (Patient $patient) {
                return (string)$patient;
            },
            $patients
        );
        asort($patientNames);
        array_values($patientNames);

        return new JsonResponse(
            json_encode($patientNames)
        );
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
            null,
            $entity,
            'app.patient.message.created',
            'app.patient.message.updated',
            'patient_view',
            $this->get('app.hasher')->encodeObject($entity)
        );
    }

    protected function updateNotifications($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.patient_notifications.form'),
            '@App/Patient/include/viewForm.html.twig',
            $entity,
            'app.patient.message.notifications_settings_update',
            'app.patient.message.notifications_settings_update',
            'patient_view',
            $this->get('app.hasher')->encodeObject($entity)
        );
    }
}
