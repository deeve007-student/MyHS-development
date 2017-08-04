<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Dashboard controller.
 *
 * @Route("dashboard")
 */
class DashboardController extends Controller
{

    /**
     * @Route("/", name="dashboard_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/widget/calendar/{date}", name="dashboard_widget_calendar", defaults={"date"=null}, options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Dashboard/widgetCalendarContents.html.twig")
     */
    public function widgetCalendarAction(Request $request, $date)
    {
        $eventUtils = $this->get('app.event_utils');

        if (!$date) {
            $date = new \DateTime();
        } else {
            $date = \DateTime::createFromFormat($this->get('app.formatter')->getDateTimeBackendFormat(), $date);
        }

        return array(
            'eventUtils' => $eventUtils,
            'intervals' => $eventUtils->getWorkDayIntervals($date),
            'resources' => $eventUtils->getResources(),
            'date' => $date,
            'datePrev' => (clone $date)->modify('-1 day')->format($this->get('app.formatter')->getDateTimeBackendFormat()),
            'dateNext' => (clone $date)->modify('+1 day')->format($this->get('app.formatter')->getDateTimeBackendFormat()),
        );
    }

    /**
     * @Route("/widget/invoice", name="dashboard_widget_invoice", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Dashboard/widgetInvoiceContents.html.twig")
     */
    public function widgetInvoiceAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');
        $qb = $em->getRepository('AppBundle:Invoice')->createQueryBuilder('i');

        return array(
            'invoices' => $qb
                ->where($qb->expr()->in('i.status', ':statuses'))
                ->setParameter('statuses', array(Invoice::STATUS_PENDING, Invoice::STATUS_OVERDUE))
                ->getQuery()->getResult(),
        );
    }

}
