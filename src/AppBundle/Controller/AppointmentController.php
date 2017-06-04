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
            return array(
                'title' => (string)$appointment,
                'start' => $appointment->getStart()->format(\DateTime::ATOM),
                'end' => $appointment->getEnd()->format(\DateTime::ATOM),
                'column' => 0,
                'editable' => 1,
                //'color' => 'yellow',
                //'textColor' => 'black',
            );
        }, $this->getDoctrine()->getManager()->getRepository('AppBundle:Appointment')->findAll());

        /*
         [
                'title' => 'Test 1',
                'start' => '2017-06-04T10:00:00',
                'end' => '2017-06-04T10:29:00',
                'column' => 0,
                'editable' => 1,
                'color' => 'yellow',
                'textColor' => 'black',
            ],
         */

        return new JsonResponse($data);
    }

    /**
     * Creates a new appointment entity.
     *
     * @Route("/new", name="appointment_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Appointment/update.html.twig")
     */
    public function createAction(Request $request)
    {
        $product = $this->get('app.entity_factory')->createAppointment();

        return $this->update($product);
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
