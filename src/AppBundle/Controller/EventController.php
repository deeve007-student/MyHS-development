<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 06.06.2017
 * Time: 10:08
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Event;
use AppBundle\Entity\UnavailableBlock;
use AppBundle\Utils\EventUtils;
use AppBundle\Validator\EventNotOverlap;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\Common\Util\Inflector;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Validator\ConstraintViolationInterface;
use UserBundle\Entity\User;

/**
 * Appointment controller.
 *
 * @Route("event")
 */
class EventController extends Controller
{

    /**
     * Displays a form to edit an existing event entity.
     *
     * @Route("/{id}/update", name="event_update", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function updateAction(Request $request, Event $event)
    {
        /** @var Router $router */
        $router = $this->get('router');
        $hasher = $this->get('app.hasher');
        $translator = $this->get('translator');
        $eventUtils = $this->get('app.event_utils');

        $route = $eventUtils->getRealEventRoutePrefix($event) . '_update';

        $classTranslation = $this->getEventUtils()->getClassTranslation($eventUtils->getRealEvent($event));

        $routeParams = array();
        $routeParams['id'] = $hasher->encodeObject($this->get('app.event_utils')->getRealEvent($event));

        if ($request->get('date') !== null) {
            $routeParams['date'] = $request->get('date');
        }
        if ($request->get('resourceId') !== null) {
            $routeParams['resourceId'] = $request->get('resourceId');
        }
        if ($request->get('rescheduleDate') !== null) {
            $routeParams['rescheduleDate'] = $request->get('rescheduleDate');
            $routeParams['title'] = 'app.' . $classTranslation . '.reschedule';
        }
        if ($request->get('bookAgainDate') !== null) {
            $routeParams['bookAgainDate'] = $request->get('bookAgainDate');
            $routeParams['title'] = 'app.' . $classTranslation . '.book_again';
        }

        $url = $router->generate($route, $routeParams);
        return new RedirectResponse($url);
    }

    /**
     * Deletes an event entity.
     *
     * @Route("/{id}/delete", name="event_delete", options={"expose"=true})
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Event $event)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        return new JsonResponse();
    }

    /**
     * Displays a viw mode for event entity.
     *
     * @Route("/{id}/view", name="event_view", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function viewAction(Request $request, Event $event)
    {
        /** @var Router $router */
        $router = $this->get('router');
        $hasher = $this->get('app.hasher');

        $route = $this->get('app.event_utils')->getRealEventRoutePrefix($event) . '_view';

        $url = $router->generate($route, array('id' => $hasher->encodeObject($this->get('app.event_utils')->getRealEvent($event))));
        return new RedirectResponse($url);
    }

    /**
     * @Route("/list", name="event_list", options={"expose"=true})
     * @Method("GET")
     */
    public function eventsAction(Request $request)
    {
        $eventUtils = $this->get('app.event_utils');
        $events = $eventUtils->getActiveEventsQb()->getQuery()->getResult();
        $eventUtils->processMirrors($events);

        /*
        $invisibleEvent = new UnavailableBlock();
        $start = \DateTime::createFromFormat('Y-m-d',$request->get('start'),new \DateTimeZone($user->getTimezone()));
        $start = $start->setTime(9,15);
        $end = clone $start;
        $end = $end->modify('+45 minutes');
        $invisibleEvent->setStart($start);
        $invisibleEvent->setEnd($end);
        $resources = $user->getCalendarSettings()->getResources()->toArray();
        $resource = array_shift($resources);
        $invisibleEvent->setResource($resource);
        */

        $data = array_map(function (Event $event) use ($eventUtils) {
            return $eventUtils->serializeEvent($event);
        }, $events);

        return new JsonResponse($data);
    }

    /**
     * Process calendar drop event
     *
     * @Route("/{id}/reschedule/{delta}/{column}", name="event_reschedule", options={"expose"=true})
     * @Method("POST")
     */
    public function rescheduleAction(Request $request, Event $event, $delta, $column)
    {
        $delta = (int)$delta;
        if ($delta >= 0) {
            $delta = '+ ' . $delta . ' minute';
        } else {
            $delta = $delta . ' minute';
        }
        $event->setStart((clone $event->getStart())->modify($delta));
        $event->setEnd((clone $event->getEnd())->modify($delta));
        $event->setResource($this->get('app.event_utils')->getResourceByNumber($column));

        $this->validate($event);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(
            array(
                'event' => $this->get('app.event_utils')->serializeEvent($event),
                'class' => $this->getEventUtils()->getClassTranslation($event),
            )
        );
    }

    /**
     * Resize calendar event
     *
     * @Route("/{id}/resize/{stop}", name="event_resize", options={"expose"=true})
     * @Method("POST")
     */
    public function resizeAction(Request $request, Event $event, $stop)
    {
        $dt = $this->getEventUtils()->parseDateFromUTC($stop);
        $event->setEnd($dt);

        $this->validate($event);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(array('event' => $this->get('app.event_utils')->serializeEvent($event)));
    }

    protected function validate(Event $event)
    {
        $errors = $this->get('validator')->validate($event, new EventNotOverlap());
        if (count($errors)) {
            throw new \Exception('Events cannot overlap each other');
        }
    }


    /**
     * Returns event's end time by passed start date and duration.
     *
     * @Route("/end-time/{h}/{m}/{ampm}/{d}", name="event_end_time", options={"expose"=true})
     * @Method("POST")
     */
    public function viewPriceAction(Request $request, $h, $m, $ampm, $d)
    {
        $start = \DateTime::createFromFormat('g:i A', $h . ':' . $m . ' ' . $ampm);
        $end = (clone $start)->modify('+' . $d . ' minutes');

        return new JsonResponse(
            array(
                'h' => $end->format('g'),
                'm' => $end->format('i'),
                'ampm' => $end->format('A'),
            )
        );
    }

}
