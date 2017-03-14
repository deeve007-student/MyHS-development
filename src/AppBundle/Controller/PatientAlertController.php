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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/new/{id}", name="patient_alert_create")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:PatientAlert:update.html.twig")
     */
    public function createAction(Request $request, Patient $patient)
    {
        $patientAlert = $this->get('app.entity_factory')->createPatientAlert($patient);

        return $this->update($patientAlert);
    }

    /**
     * Finds and displays a patient entity.
     *
     * @Route("/{id}", name="patient_alert_view")
     * @Method("GET")
     * @Template()
     */
    public function viewAction(PatientAlert $patientAlert)
    {
        return array(
            'entity' => $patientAlert,
        );
    }

    /**
     * Displays a form to edit an existing patient entity.
     *
     * @Route("/update/{id}", name="patient_alert_update")
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
     * @Route("/delete/{id}", name="patient_alert_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, PatientAlert $patientAlert)
    {
        $patientId = $patientAlert->getPatient()->getId();

        $em = $this->getDoctrine()->getManager();
        $em->remove($patientAlert);
        $em->flush();

        return $this->redirectToRoute('patient_view', array('id' => $patientId));
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.patient_alert.form'),
            $entity,
            'patient_view',
            $entity->getPatient()->getId()
        );
    }
}
