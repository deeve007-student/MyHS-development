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