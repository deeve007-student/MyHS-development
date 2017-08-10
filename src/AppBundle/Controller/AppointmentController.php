<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 18:59
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\CancelReason;
use AppBundle\Entity\Event;
use AppBundle\Entity\Patient;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\VarDumper\VarDumper;

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
     * @Route("/new/{date}/{resourceId}", defaults={"date"=null, "resourceId"=null, "patient"=null}, name="appointment_create", options={"expose"=true})
     * @Route("/new/{date}/{resourceId}/{patient}", defaults={"date"=null, "resourceId"=null, "patient"=null}, name="appointment_create_for_patient", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Appointment/update.html.twig")
     */
    public function createAction(Request $request, $date, $resourceId)
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

        if ($resourceId !== null) {
            $appointment->setResource($this->getUser()->getCalendarData()->getResources()->toArray()[$resourceId]);
        }

        return $this->update($appointment);
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

        /** @var QueryBuilder $cancelReasonsQb */
        $cancelReasonsQb = $this->getDoctrine()->getManager()->getRepository('AppBundle:CancelReason')->createQueryBuilder('r');
        $cancelReasons = $cancelReasonsQb->orderBy('r.position', 'ASC')->getQuery()->getResult();

        return array(
            'entity' => $appointment,
            'eventClass' => Event::class,
            'nextAppointment' => $nextAppointment,
            'defaultTemplate' => $tnDefaultTemplate,
            'cancelReasons' => $cancelReasons,
        );
    }

    /**
     * Returns prev and next patient appointments
     *
     * @Route("/patient-widget/{id}", name="patient_appointment_widget", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function patientWidgetAction(Patient $patient)
    {
        $eventUtils = $this->get('app.event_utils');

        $nextAppointment = null;
        $prevAppointment = null;

        $nextAppointments = $eventUtils->getNextAppointmentsByPatientQb(null, $patient)->getQuery()->getResult();
        $prevAppointments = $eventUtils->getPrevAppointmentsByPatientQb(null, $patient)->getQuery()->getResult();

        if ($nextAppointments && isset($nextAppointments[0])) {
            $nextAppointment = $nextAppointments[0];
        }

        if ($prevAppointments && isset($prevAppointments[0])) {
            $prevAppointment = $prevAppointments[0];
        }

        return array(
            'patient' => $patient,
            'nextAppointment' => $nextAppointment,
            'prevAppointment' => $prevAppointment,
            'eventClass' => Event::class,
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

        if ($request->get('rescheduleDate') !== null) {
            $dt = \DateTime::createFromFormat('Y-m-d\TH:i:s', $request->get('rescheduleDate'));
            $duration = $appointment->getDurationInMinutes();

            $appointment->setStart($dt);
            $appointment->setEnd((clone $dt)->modify('+ ' . $duration . ' minutes'));
        }

        if ($request->get('bookAgainDate') !== null) {
            $newAppointment = clone $appointment;
            $dt = \DateTime::createFromFormat('Y-m-d\TH:i:s', $request->get('bookAgainDate'));
            $duration = $appointment->getDurationInMinutes();

            $newAppointment->setStart($dt);
            $newAppointment->setEnd((clone $dt)->modify('+ ' . $duration . ' minutes'));

            $appointment = $newAppointment;
        }

        if ($request->get('resourceId') !== null) {
            $appointment->setResource($this->getUser()->getCalendarData()->getResources()->toArray()[$request->get('resourceId')]);
        }

        return $this->update($appointment);
    }

    /**
     * Cancels appointment.
     *
     * @Route("/{id}/cancel/{reason}", name="appointment_cancel", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function cancelAction(Request $request, Appointment $appointment, CancelReason $reason)
    {
        $appointment->setReason($reason);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse();
    }

    /**
     * @Route("/{id}/arrived", name="appointment_patient_arrived", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function patientArrivedAction(Request $request, Appointment $appointment)
    {
        if ($appointment->getPatientArrived()) {
            $appointment->setPatientArrived(false);
            $state = 0;
        } else {
            $appointment->setPatientArrived(true);
            $state = 1;
        }
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(array('state' => $state));
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
