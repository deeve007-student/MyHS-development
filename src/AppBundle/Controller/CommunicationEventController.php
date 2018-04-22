<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:14
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Event;
use AppBundle\Entity\CommunicationEvent;
use AppBundle\Entity\CommunicationEventTreatment;
use AppBundle\Entity\Message;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Refund;
use AppBundle\Utils\AppNotificator;
use AppBundle\Utils\FilterUtils;
use AppBundle\Utils\Templater;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;

/**
 * CommunicationEvent controller.
 *
 * Route("communication-event")
 */
class CommunicationEventController extends Controller
{

    /**
     * Creates a new communication event entity.
     *
     * @Route("/communication-event/new", name="communication_event_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/CommunicationEvent/update.html.twig")
     */
    public function createAction(Request $request)
    {
        $communicationEvent = $this->get('app.entity_factory')->createCommunicationEvent();

        return $this->update($communicationEvent);
    }

    /**
     * Creates a new communication event entity from patient screen.
     *
     * @Route("/patient/{patient}/communication-event/new", name="communication_event_create_patient", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/CommunicationEvent/update.html.twig")
     */
    public function createFromPatientAction(Request $request, Patient $patient)
    {
        $communicationEvent = $this->get('app.entity_factory')->createCommunicationEvent($patient);

        return $this->updatePatient($communicationEvent);
    }

    /**
     * Displays a form to edit an existing communication event entity.
     *
     * @Route("/communication-event/{id}/update", name="communication_event_update", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/CommunicationEvent/update.html.twig")
     */
    public function updateAction(Request $request, CommunicationEvent $communicationEvent)
    {
        return $this->update($communicationEvent);
    }

    /**
     * Displays a form to edit an existing communication event entity from patient screen.
     *
     * @Route("/patient/{patient}/communication-event/{id}/update", name="communication_event_update_patient", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/CommunicationEvent/update.html.twig")
     */
    public function updatePatientAction(Request $request, CommunicationEvent $communicationEvent)
    {
        return $this->updatePatient($communicationEvent);
    }

    /**
     * Deletes a communication event entity.
     *
     * @Route("/communication-event/{id}/delete", name="communication_event_delete", options={"expose"=true})
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, CommunicationEvent $communicationEvent)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($communicationEvent);
        $em->flush();

        $this->addFlash(
            'success',
            'app.communication_event.message.deleted'
        );

        return $this->redirectToRoute('message_log_index');
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.communication_event.form'),
            "@App/CommunicationEvent/include/form.html.twig",
            $entity,
            'app.communication_event.message.created',
            'app.communication_event.message.updated',
            'communication_event_view',
            $this->get('app.hasher')->encodeObject($entity)
        );
    }

    protected function updatePatient($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.communication_event.form'),
            "@App/CommunicationEvent/include/formPatient.html.twig",
            $entity,
            'app.communication_event.message.created',
            'app.communication_event.message.updated',
            'communication_event_view',
            $this->get('app.hasher')->encodeObject($entity)
        );
    }

}
