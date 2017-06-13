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

/**
 * Appointment controller.
 *
 * @Route("event")
 */
class EventController extends Controller
{

    protected function getRealEventClassName(Event $event)
    {
        return ClassUtils::getClass($event);
    }

    protected function getRealEvent(Event $event)
    {
        return $this->getDoctrine()->getManager()->getRepository($this->getRealEventClassName($event))->find($event->getId());
    }

    protected function getRealEventRoutePrefix(Event $event)
    {
        $realEventClassParts = explode('\\', $this->getRealEventClassName($event));
        $className = array_pop($realEventClassParts);
        return Inflector::tableize($className);
    }

    /**
     * Displays a form to edit an existing appointment entity.
     *
     * @Route("/{id}/update", name="event_update", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function updateAction(Request $request, Event $event)
    {
        /** @var Router $router */
        $router = $this->get('router');
        $hasher = $this->get('app.hasher');

        $route = $this->getRealEventRoutePrefix($event) . '_update';

        $url = $router->generate($route, array('id' => $hasher->encodeObject($this->getRealEvent($event))));
        return new RedirectResponse($url);
    }

    /**
     * @Route("/list", name="event_list", options={"expose"=true})
     * @Method("GET")
     */
    public function eventsAction()
    {
        $eventUtils = $this->get('app.event_utils');

        $data = array_map(function (Event $event) use ($eventUtils) {
            return $eventUtils->serializeEvent($event);
        }, $this->getDoctrine()->getManager()->getRepository('AppBundle:Event')->findAll());

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

        $this->checkOverlap($event);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(array('event' => $this->get('app.event_utils')->serializeEvent($event)));
    }

    /**
     * Resize calendar event
     *
     * @Route("/{id}/resize/{stop}", name="event_resize", options={"expose"=true})
     * @Method("POST")
     */
    public function resizeAction(Request $request, Event $event, $stop)
    {
        $dt = \DateTime::createFromFormat('Y-m-d\TH:i:s', $stop);
        $event->setEnd($dt);

        $this->checkOverlap($event);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse();
    }

    protected function checkOverlap(Event $event)
    {
        if ($this->get('app.event_utils')->isOverlapping($event)) {
            throw new \Exception('Evens cannot overlap each other');
        }
    }

}
