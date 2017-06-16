<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 18:59
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Event;
use AppBundle\Entity\Patient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Appointment controller.
 *
 * @Route("appointment")
 */
class AppointmentController extends Controller
{

    /**
     * Creates a new appointment entity.
     *
     * @Route("/new/{date}", defaults={"date"=null, "patient"=null}, name="appointment_create", options={"expose"=true})
     * @Route("/new/{date}/patient/{patient}", defaults={"date"=null, "patient"=null}, name="appointment_create_for_patient", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Appointment/update.html.twig")
     */
    public function createAction(Request $request, $date)
    {
        $appointment = $this->get('app.entity_factory')->createAppointment();

        if ($patientId = $this->get('request_stack')->getCurrentRequest()->get('patient')) {
            $patientId = $this->get('app.hasher')->decode($patientId, Patient::class);
            $patient = $this->getDoctrine()->getManager()->getRepository('AppBundle:Patient')->find($patientId);
            $appointment->setPatient($patient);
        }

        if ($date) {
            $this->get('app.event_utils')->setEventDates($appointment, $date);
        }

        return $this->update($appointment);
    }

    /**
     * Creates a new appointment and new patient entity.
     *
     * @Route("/new-with-patient/{date}", defaults={"date"=null}, name="appointment_create_with_new_patient", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Appointment/updateWithPatient.html.twig")
     */
    public function createWithPatientAction(Request $request, $date)
    {
        $appointment = $this->get('app.entity_factory')->createAppointment();

        $patient = $this->get('app.entity_factory')->createPatient();
        $appointment->setPatient($patient);

        if ($date) {
            $this->get('app.event_utils')->setEventDates($appointment, $date);
        }

        return $this->updateWithPatient($appointment);
    }

    /**
     * Finds and displays an appointment entity.
     *
     * @Route("/{id}", name="appointment_view")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function viewAction(Appointment $appointment)
    {
        $tnDefaultTemplate = $this->getDoctrine()->getManager()->getRepository("AppBundle:TreatmentNoteTemplate")->findOneBy(
            array(
                'default' => true,
            )
        );

        $nextAppointment = null;
        $nextAppointments = $this->get('app.event_utils')->getNextAppointmentsByPatientQb($appointment, $appointment->getPatient())->getQuery()->getResult();
        if (count($nextAppointments)) {
            $nextAppointment = array_shift($nextAppointments);
        }

        return array(
            'entity' => $appointment,
            'eventClass' => Event::class,
            'nextAppointment' => $nextAppointment,
            'defaultTemplate' => $tnDefaultTemplate,
        );
    }

    /**
     * Displays a form to edit an existing appointment entity.
     *
     * @Route("/{id}/update", name="appointment_update", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Appointment/update.html.twig")
     */
    public function updateAction(Request $request, Appointment $appointment)
    {
        if ($date = $request->get('date')) {
            $this->get('app.event_utils')->setEventDates($appointment, $date);
        }

        return $this->update($appointment);
    }

    /**
     * @Route("/{id}/arrived", name="appointment_patient_arrived", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function patientArrivedAction(Request $request, Appointment $appointment)
    {
        $appointment->setPatientArrived(true);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse();
    }

    /**
     * Displays a form to edit an existing appointment entity with new patient form.
     *
     * @Route("/{id}/update-with-patient", name="appointment_update_with_patient", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Appointment/updateWithPatient.html.twig")
     */
    public function updateWithPatientAction(Request $request, Appointment $appointment)
    {
        return $this->updateWithPatient($appointment);
    }

    /**
     * Deletes an appointment entity.
     *
     * @Route("/{id}/delete", name="appointment_delete", options={"expose"=true})
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Appointment $appointment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($appointment);
        $em->flush();

        $this->addFlash(
            'success',
            'app.appointment.message.deleted'
        );

        return $this->redirectToRoute('calendar_index');
    }

    protected function update($entity, $additionalData = array())
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.appointment.form'),
            '@App/Appointment/include/form.html.twig',
            $entity,
            'app.appointment.message.created',
            'app.appointment.message.updated',
            'calendar_index',
            null,
            null,
            $additionalData
        );
    }

    protected function updateWithPatient($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.appointment_with_patient.form'),
            '@App/Appointment/include/formWithPatient.html.twig',
            $entity,
            'app.appointment.message.created',
            'app.appointment.message.updated',
            'calendar_index'
        );
    }
}
