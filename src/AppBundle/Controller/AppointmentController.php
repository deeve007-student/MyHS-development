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
use AppBundle\Entity\InvoiceTreatment;
use AppBundle\Entity\Patient;
use AppBundle\Entity\TreatmentNote;
use AppBundle\Entity\TreatmentPackCredit;
use AppBundle\Utils\EntityFactory;
use AppBundle\Utils\TreatmentPackUtils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

        $additionalData['title'] = 'app.appointment.new';
        $additionalData['submit'] = 'app.appointment.new';

        if ($patientId = $this->get('request_stack')->getCurrentRequest()->get('patient')) {
            $patientId = $this->get('app.hasher')->decode($patientId, Patient::class);
            $patient = $this->getDoctrine()->getManager()->getRepository('AppBundle:Patient')->find($patientId);
            $appointment->setPatient($patient);
        }

        if ($packId = $this->get('request_stack')->getCurrentRequest()->get('pack')) {
            $packId = $this->get('app.hasher')->decode($packId, TreatmentPackCredit::class);
            /** @var TreatmentPackCredit $pack */
            $pack = $this->getDoctrine()->getManager()->getRepository('AppBundle:TreatmentPackCredit')->find($packId);
            $appointment->setTreatment($pack->getTreatment());
            $appointment->setPatient($pack->getPatient());
            $appointment->setInvoice($pack->getInvoiceProduct()->getInvoice());
            $appointment->setPackId($pack->getId());
        }

        if ($date) {
            $this->get('app.event_utils')->setEventDates($appointment, $date);
            if ($appointment->getTreatment()) {
                $appointment->setEnd((clone $appointment->getStart())->modify('+ ' . $appointment->getTreatment()->getDuration() . ' minutes'));
            }
        }

        if ($resourceId !== null) {
            $appointment->setResource($this->getUser()->getCalendarSettings()->getResources()->toArray()[$resourceId]);
        }

        $additionalData = $this->getEventAdditionalData($request, $additionalData);

        $result = $this->update($appointment, $additionalData);

        $form = $this->get('app.appointment.form');
        if ($form->isSubmitted() && $form->isValid()) {
            if ($packId = $form->get('packId')->getData()) {
                $pack = $this->getDoctrine()->getRepository('AppBundle:TreatmentPackCredit')->findOneBy(array('id' => $packId));
                $pack->setAmountSpend($pack->getAmountSpend() + 1);
                $this->getDoctrine()->getManager()->flush();
            }
        }

        return $result;
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
            'defaultTemplate' => $this->get('app.treatment_note_utils')->getDefaultTemplate(),
            'cancelReasons' => $cancelReasons,
            'treatmentPackUtils' => $this->get('app.treatment_pack_utils'),
        );
    }

    /**
     * Links appointment to treatment pack invoice.
     *
     * @Route("/{id}/use-pack", name="appointment_link_to_treatment_pack", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function linkWithPackInvoiceAction(Appointment $appointment)
    {
        /** @var TreatmentPackUtils $tpu */
        $tpu = $this->get('app.treatment_pack_utils');

        /** @var Router $router */
        $router = $this->get('router');

        $pack = $tpu->getAvailableTreatmentPack($appointment->getPatient(), $appointment->getTreatment());
        $invoice = $pack->getInvoiceProduct()->getInvoice();

        $appointment->setInvoice($invoice);
        $pack->setAmountSpend($pack->getAmountSpend() + 1);

        $this->getDoctrine()->getManager()->flush();

        $invoiceUrl = $router->generate('invoice_view', array(
            'id' => $this->get('app.hasher')->encodeObject($invoice),
        ));

        return new JsonResponse(array(
            'invoiceUrl' => $invoiceUrl,
        ));
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
        $additionalData['title'] = 'app.appointment.edit';
        $additionalData['submit'] = 'app.action.save';

        if ($request->get('rescheduleDate') !== null) {
            $dt = $this->getEventUtils()->parseDateFromUTC($request->get('rescheduleDate'));
            $duration = $appointment->getDurationInMinutes();
            $additionalData['reschedule'] = true;

            $appointment->setStart($dt);
            $appointment->setEnd((clone $dt)->modify('+ ' . $duration . ' minutes'));
        }

        if ($request->get('bookAgainDate') !== null) {
            $newAppointment = clone $appointment;
            $dt = $this->getEventUtils()->parseDateFromUTC($request->get('bookAgainDate'));
            $duration = $appointment->getDurationInMinutes();

            $newAppointment->setStart($dt);
            $newAppointment->setEnd((clone $dt)->modify('+ ' . $duration . ' minutes'));

            $appointment = $newAppointment;
        }

        if ($request->get('resourceId') !== null) {
            $appointment->setResource($this->getUser()->getCalendarSettings()->getResources()->toArray()[$request->get('resourceId')]);
        }

        $additionalData = $this->getEventAdditionalData($request, $additionalData);

        return $this->update($appointment, $additionalData);

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
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Router $router */
        $router = $this->get('router');

        /** @var TreatmentPackUtils $tpu */
        $tpu = $this->get('app.treatment_pack_utils');

        /** @var EntityFactory $ef */
        $ef = $this->get('app.entity_factory');

        if ($appointment->getPatientArrived()) {
            $appointment->setPatientArrived(false);
            $arrived = 0;
        } else {
            $appointment->setPatientArrived(true);
            $arrived = 1;
        }

        $tnUrl = '';
        if (!$appointment->getTreatmentNote() && $arrived) {
            $tn = $ef->createTreatmentNote($appointment->getPatient(), $this->get('app.treatment_note_utils')->getDefaultTemplate());
            $tn->setAppointment($appointment);
            $tn->setStatus(TreatmentNote::STATUS_DRAFT);
            $em->persist($tn);
            $em->flush();

            $tnUrl = $router->generate('treatment_note_view', array(
                'patient' => $this->get('app.hasher')->encodeObject($appointment->getPatient()),
                'treatmentNote' => $this->get('app.hasher')->encodeObject($tn),
            ));
        }

        $invoiceUrl = '';
        if (!$appointment->getInvoice() && $arrived && !$tpu->getAvailableTreatmentPack($appointment->getPatient(), $appointment->getTreatment())) {
            $invoice = $ef->createInvoice($appointment->getPatient());
            $invoice->addAppointment($appointment);

            $invoiceItem = new InvoiceTreatment();
            $invoiceItem->setTreatment($appointment->getTreatment());
            $invoiceItem->setQuantity(1);
            $invoiceItem->setPrice($appointment->getTreatment()->getPrice($appointment->getPatient()->getConcession()));

            $invoice->addInvoiceTreatment($invoiceItem);

            $em->persist($invoice);
            $em->flush();

            $invoiceUrl = $router->generate('invoice_view', array(
                'id' => $this->get('app.hasher')->encodeObject($invoice),
            ));
        }

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(array(
            'state' => $arrived,
            'newTnUrl' => $tnUrl,
            'newInvoiceUrl' => $invoiceUrl,
        ));
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
        $additionalData['title'] = 'app.appointment.edit';
        $additionalData['submit'] = 'app.action.save';
        $additionalData = $this->getEventAdditionalData($request, $additionalData);
        return $this->updateWithPatient($appointment, $additionalData);
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

    protected
    function update($entity, $additionalData = array())
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

    protected
    function updateWithPatient($entity, $additionalData = array())
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.appointment_with_patient.form'),
            '@App/Appointment/include/formWithPatient.html.twig',
            $entity,
            'app.appointment.message.created',
            'app.appointment.message.updated',
            'calendar_index',
            null,
            null,
            $additionalData
        );
    }
}
