<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:38
 */

namespace AppBundle\Controller;

use AppBundle\Entity\EventResource;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

/**
 * CalendarData controller.
 */
class CalendarDataController extends Controller
{
    /**
     * Displays a form to edit an existing concession entity.
     *
     * @Route("/settings/calendar", name="calendar_data_update", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request)
    {
        if ($request->getMethod() == 'POST' && $request->get('app_calendar_data')) {

            $columns = $request->get('app_calendar_data')['resources'] - 1;

            $translator = $this->get('translator.default');

            /** @var QueryBuilder $resourcesQb */
            $resourcesQb = $this->getDoctrine()->getManager()->getRepository('AppBundle:EventResource')->createQueryBuilder('r');
            $resources = $resourcesQb->where($resourcesQb->expr()->neq('r.default', ':true'))
                ->setParameter('true', true)
                ->getQuery()->getResult();

            /** @var QueryBuilder $resourcesQb */
            $resourcesQb = $this->getDoctrine()->getManager()->getRepository('AppBundle:EventResource')->createQueryBuilder('r');
            $maxPosition = ($resourcesQb->orderBy('r.position', 'DESC')->getQuery()->setMaxResults(1)->getOneOrNullResult())->getPosition();

            if ($columns < count($resources)) {
                for ($n = 0; $n <= $columns; $n++) {
                    $resToDel = array_pop($resources);
                    $this->getDoctrine()->getManager()->remove($resToDel);
                }
            } elseif ($columns > count($resources)) {
                for ($n = count($resources) + 1; $n <= $columns; $n++) {
                    $resource = new EventResource();
                    $resource->setName($translator->trans('app.event_resource.defaults.resource_name', ["%n%" => $n + 1]))
                        ->setPosition($maxPosition++)
                        ->setCalendarData($this->getUser()->getCalendarData());
                    $this->getDoctrine()->getManager()->persist($resource);
                }
            }
        }

        return $this->update($this->getUser()->getCalendarData());
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.calendar_data.form'),
            'AppBundle:CalendarData:update.html.twig',
            $entity,
            '',
            'app.calendar_data.message.updated',
            'calendar_data_update'
        );
    }
}
