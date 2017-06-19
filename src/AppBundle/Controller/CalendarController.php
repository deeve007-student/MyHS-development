<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 17:25
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventResource;
use AppBundle\Entity\Patient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

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
            'eventUtils' => $this->get('app.event_utils'),
            'resources' => json_encode(array_map(
                function (EventResource $resource) {
                    return $resource->getName();
                },
                $this->get('app.event_utils')->getResources()
            )),
        );
    }

    /**
     * @Route("/calendar", name="calendar_index", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
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

        $this->addFlash('success', 'app.event.reschedule_pick_time');

        return $data;
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

    /**
     * Displays a form to edit an existing event entity.
     *
     * @Route("/calendar/reschedule/{event}/new-time", name="calendar_event_reschedule_pick_time", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function reschedulePickTimeAction(Request $request, Event $event)
    {
        /** @var Router $router */
        $router = $this->get('router');
        $hasher = $this->get('app.hasher');

        $route = $this->get('app.event_utils')->getRealEventRoutePrefix($event) . '_update';
        $event = $this->get('app.event_utils')->getRealEvent($event);

        $url = $router->generate($route, array('id' => $hasher->encodeObject($this->getRealEvent($event))));
        return new RedirectResponse($url);
    }
}
