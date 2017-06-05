<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 18:59
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Appointment;
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
     * @Route("/", name="appointment_index", options={"expose"=true})
     * @Method("GET")
     */
    public function indexAction()
    {

        $data = array_map(function (Appointment $appointment) {
            $event = array(
                'id' => $this->get('app.hasher')->encodeObject($appointment),
                'title' => (string)$appointment,
                'treatment' => (string)$appointment->getTreatment(),
                'description' => $appointment->getDescription() ? $appointment->getDescription() : '',
                'start' => $appointment->getStart()->format(\DateTime::ATOM),
                'end' => $appointment->getEnd()->format(\DateTime::ATOM),
                'column' => 0,
                'editable' => 1,
                'className' => 'cal-icon',
                'color' => '#D3D3D3',
                'textColor' => '#000',
            );

            if ($color = $appointment->getTreatment()->getCalendarColour()) {
                $event['color'] = $color;
                $event['textColor'] = '#fff';
            }

            return $event;
        }, $this->getDoctrine()->getManager()->getRepository('AppBundle:Appointment')->findAll());

        return new JsonResponse($data);
    }

    /**
     * Creates a new appointment entity.
     *
     * @Route("/new/{date}", defaults={"date"=null}, name="appointment_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Appointment/update.html.twig")
     */
    public function createAction(Request $request, $date)
    {
        $appointment = $this->get('app.entity_factory')->createAppointment();

        if ($date) {
            $dt = \DateTime::createFromFormat('Y-m-d\TH:i:s', $date);
            $appointment->setStart($dt);
            $appointment->setEnd($dt);
        }

        return $this->update($appointment);
    }

    /**
     * Displays a form to edit an existing appointment entity.
     *
     * @Route("/{id}/update", name="appointment_update", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Appointment $appointment)
    {
        return $this->update($appointment);
    }

    /**
     * Process calendar drop event
     *
     * @Route("/{id}/reschedule/{delta}", name="appointment_reschedule", options={"expose"=true})
     * @Method("POST")
     */
    public function rescheduleAction(Request $request, Appointment $appointment, $delta)
    {
        $delta = (int)$delta;
        if ($delta >= 0) {
            $delta = '+ ' . $delta . ' minute';
        } else {
            $delta = $delta . ' minute';
        }
        $appointment->setStart((clone $appointment->getStart())->modify($delta));
        $appointment->setEnd((clone $appointment->getEnd())->modify($delta));
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse();
    }

    /**
     * Resize calendar event
     *
     * @Route("/{id}/resize/{stop}", name="appointment_resize", options={"expose"=true})
     * @Method("POST")
     */
    public function resizeAction(Request $request, Appointment $appointment, $stop)
    {
        $dt = \DateTime::createFromFormat('Y-m-d\TH:i:s', $stop);
        $appointment->setEnd($dt);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse();
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

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.appointment.form'),
            '@App/Appointment/include/form.html.twig',
            $entity,
            'app.appointment.message.created',
            'app.appointment.message.updated',
            'calendar_index'
        );
    }
}
