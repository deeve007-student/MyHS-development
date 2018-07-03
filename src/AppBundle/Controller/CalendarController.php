<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 17:25
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Event;
use AppBundle\Entity\EventResource;
use AppBundle\Entity\Patient;
use AppBundle\Entity\TreatmentPackCredit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Calendar controller.
 *
 * @Route("")
 */
class CalendarController extends Controller
{

    protected function getCalendarResponseData()
    {
        return array(
            'today' => $this->get('session')->get('calendarDate'),
            'eventUtils' => $this->get('app.event_utils'),
            'resources' => json_encode(array_map(
                function (EventResource $resource) {
                    return $resource->getName();
                },
                $this->getUser()->getCalendarSettings()->getResources()->toArray()
            )),
        );
    }

    /**
     * @Route("/calendar", name="calendar_index", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return $this->getCalendarResponseData();
    }

    /**
     * @Route("/calendar/patient/{patient}", name="calendar_new_patient_event")
     * @Method("GET")
     * @Template("@App/Calendar/index.html.twig")
     */
    public function newPatientEventAction(Patient $patient)
    {
        $data = $this->getCalendarResponseData();
        $data['patient'] = $this->get('app.hasher')->encodeObject($patient);

        return $data;
    }

    /**
     * @Route("/calendar/reschedule/{event}", name="calendar_event_reschedule")
     * @Method("GET")
     * @Template("@App/Calendar/index.html.twig")
     */
    public function rescheduleIndexAction(Event $event)
    {
        $data = $this->getCalendarResponseData();
        $data['rescheduleEventId'] = $this->get('app.hasher')->encodeObject($event, Event::class);

        $classTranslation = $this->getEventUtils()->getClassTranslation($event);
        $data['classTranslation'] = $classTranslation;

        $this->addFlash('success', 'app.' . $classTranslation . '.reschedule_pick_time');

        return $data;
    }

    /**
     * @Route("/calendar/pack/{id}", name="calendar_appointment_create_from_pack")
     * @Method("GET")
     * @Template("@App/Calendar/index.html.twig")
     */
    public function createFromPackAction(TreatmentPackCredit $treatmentPackCredit)
    {
        $data = $this->getCalendarResponseData();
        $data['packId'] = $this->get('app.hasher')->encodeObject($treatmentPackCredit, TreatmentPackCredit::class);

        $this->addFlash('success', $this->get('translator.default')->trans('app.treatment_pack.use_calendar',
            [
                '%patient%' => (string)$treatmentPackCredit->getPatient(),
                '%treatment%' => (string)$treatmentPackCredit->getTreatment(),
            ])
        );

        return $data;
    }

    /**
     * @Route("/calendar/book-again/{event}", name="calendar_event_book_again")
     * @Method("GET")
     * @Template("@App/Calendar/index.html.twig")
     */
    public function bookAgainIndexAction(Event $event)
    {
        $data = $this->getCalendarResponseData();
        $data['bookAgainEventId'] = $this->get('app.hasher')->encodeObject($event, Event::class);
        $classTranslation = $this->getEventUtils()->getClassTranslation($event);
        $data['classTranslation'] = $classTranslation;

        $this->addFlash('success', 'app.' . $classTranslation . '.book_again_pick_time');

        return $data;
    }

    /**
     * @Route("/calendar/appointment/{event}", name="calendar_appointment_view", options={"expose"=true})
     * @Method("GET")
     * @Template("@App/Calendar/index.html.twig")
     */
    public function viewAppointmentAction(Appointment $event)
    {
        $data = $this->getCalendarResponseData();
        $data['viewEventId'] = $this->get('app.hasher')->encodeObject($event, Event::class);

        return $data;
    }

    /**
     * Displays a form to edit cloned event entity.
     *
     * @Route("/calendar/book-again/{event}/new-time", name="calendar_event_book_again_pick_time", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function bookAgainPickTimeAction(Request $request, Event $event)
    {
        /** @var Event $event */
        $event = clone $event;

        /** @var Router $router */
        $router = $this->get('router');
        $hasher = $this->get('app.hasher');

        $route = $this->get('app.event_utils')->getRealEventRoutePrefix($event) . '_update';
        $event = $this->get('app.event_utils')->getRealEvent($event);

        $url = $router->generate($route, array('id' => $hasher->encodeObject($this->get('app.event_utils')->getRealEvent($event))));
        return new RedirectResponse($url);
    }

    /**
     * Update calendar view range
     *
     * @Route("/calendar/range/{days}", name="calendar_update_view_range", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function updateViewRangeAction(Request $request, $days)
    {
        $request->getSession()->set('calendar_range', $days);
        return new JsonResponse();
    }
}
