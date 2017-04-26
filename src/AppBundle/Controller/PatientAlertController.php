<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 06.03.2017
 * Time: 11:46
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Patient;
use AppBundle\Entity\PatientAlert;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * PatientAlert controller.
 *
 * @Route("patient-alert")
 */
class PatientAlertController extends Controller
{

    /**
     * Creates a new patient entity.
     *
     * @Route("/new/{id}", name="patient_alert_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("AppBundle:PatientAlert:update.html.twig")
     */
    public function createAction(Request $request, Patient $patient)
    {
        $patientAlert = $this->get('app.entity_factory')->createPatientAlert($patient);

        return $this->update($patientAlert);
    }

    /**
     * List patient alerts.
     *
     * @Route("/list/{id}", name="patient_alert_list", options={"expose"=true})
     * @Method("GET")
     * @Template("@App/PatientAlert/list.html.twig")
     */
    public function listAction(Patient $patient)
    {
        return array(
            'patient' => $patient,
        );
    }

    /**
     * Displays a form to edit an existing patient entity.
     *
     * @Route("/{id}/update", name="patient_alert_update", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, PatientAlert $patientAlert)
    {
        return $this->update($patientAlert);
    }

    /**
     * Deletes a patient entity.
     *
     * @Route("/{id}/delete", name="patient_alert_delete", options={"expose"=true})
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, PatientAlert $patientAlert)
    {
        $patient = $patientAlert->getPatient();

        $em = $this->getDoctrine()->getManager();
        $em->remove($patientAlert);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            return new Response();
        }

        $this->addFlash(
            'success',
            'app.patient_alert.message.deleted'
        );

        return $this->redirectToRoute(
            'patient_view',
            array(
                'id' => $this->get('app.hasher')->encodeObject($patient),
            )
        );
    }

    protected function update(PatientAlert $entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.patient_alert.form'),
            '@App/PatientAlert/include/form.html.twig',
            $entity,
            'app.patient_alert.message.created',
            'app.patient_alert.message.updated',
            'patient_view',
            $this->get('app.hasher')->encodeObject($entity->getPatient())
        );
    }
}
