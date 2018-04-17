<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CommunicationEvent;
use AppBundle\Entity\Event;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\Message;
use AppBundle\Entity\TreatmentNote;
use AppBundle\Entity\WidgetState;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            $date = \DateTime::createFromFormat($this->get('app.formatter')->getMomentDateBackendFormat(), $date);
        }

        $weeks = 2;
        $deep = (7 * $weeks) - 1;

        $datesPrev = array();
        for ($n = $deep; $n > 0; $n--) {
            $dt = (clone $date)->modify('-' . $n . ' days');
            $datesPrev[] = $dt;
        }

        $datesNext = array();
        for ($n = 1; $n < $deep + 1; $n++) {
            $dt = (clone $date)->modify('+' . $n . ' days');
            $datesNext[] = $dt;
        }

        $res = array(
            'eventUtils' => $eventUtils,
            'eventClass' => Event::class,
            'resources' => $eventUtils->getResources(),
            'date' => $date,
            'dates' => array_merge($datesPrev, array($date), $datesNext),
        );

        return $res;
    }

    /**
     * @Route("/widget/invoice", name="dashboard_widget_invoice", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Dashboard/widgetInvoiceContents.html.twig")
     */
    public function widgetInvoiceAction(Request $request)
    {
        $widgetName = 'dashboard_widget_invoice';

        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');
        $qb = $em->getRepository('AppBundle:Invoice')->createQueryBuilder('i');

        return array(
            'invoices' => $qb
                ->where($qb->expr()->in('i.status', ':statuses'))
                ->setParameter('statuses', array(Invoice::STATUS_PENDING, Invoice::STATUS_OVERDUE))
                ->getQuery()->getResult(),
            'widgetName' => $widgetName,
            'widgetState' => $this->getWidgetState($widgetName)->getState(),
        );
    }

    /**
     * @Route("/widget/communications", name="dashboard_widget_communication", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Dashboard/widgetCommunicationContents.html.twig")
     */
    public function widgetCommunicationAction(Request $request)
    {
        $widgetName = 'dashboard_widget_communication';

        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');
        $qb = $em->getRepository('AppBundle:Message')->createQueryBuilder('i');
        $qbr = $em->getRepository('AppBundle:CommunicationEvent')->createQueryBuilder('r');

        $allCommunications = array_merge(
            $qb
                ->where('i.parentMessage IS NULL')
                ->addOrderBy('i.createdAt', 'DESC')
                ->getQuery()->getResult()
            ,
            $qbr
                ->leftJoin('r.patient', 'p')
                ->orderBy('r.date', 'DESC')
                ->getQuery()->getResult()
        );

        usort($allCommunications, function ($a, $b) {
            if ($a instanceof Message) {
                $ad = $a->getCreatedAt();
            }
            if ($b instanceof Message) {
                $bd = $b->getCreatedAt();
            }
            if ($a instanceof CommunicationEvent) {
                $ad = $a->getDate();
            }
            if ($b instanceof CommunicationEvent) {
                $bd = $b->getDate();
            }
            return $ad > $bd ? -1 : 1;
        });

        return array(
            'communications' => $allCommunications,
            'widgetName' => $widgetName,
            'widgetState' => $this->getWidgetState($widgetName)->getState(),
        );
    }

    /**
     * @Route("/widget/recall", name="dashboard_widget_recall", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Dashboard/widgetRecallContents.html.twig")
     */
    public function widgetRecallAction(Request $request)
    {
        $widgetName = 'dashboard_widget_recall';

        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $todayQb = $em->getRepository('AppBundle:Recall')->createQueryBuilder('r');
        $prevQb = $em->getRepository('AppBundle:Recall')->createQueryBuilder('r');

        $todayRecalls = $todayQb
            ->where('r.date = :today')
            ->setParameter('today', (new \DateTime())->format('Y-m-d'))
            ->andWhere($todayQb->expr()->isNull('r.completed'))
            ->orderBy('r.date', 'DESC')
            ->getQuery()->getResult();

        $prevRecalls = $prevQb
            ->where('r.date < :today')
            ->setParameter('today', (new \DateTime())->format('Y-m-d'))
            ->andWhere($prevQb->expr()->isNull('r.completed'))
            ->orderBy('r.date', 'DESC')
            ->getQuery()->getResult();

        return array(
            'today_recalls' => $todayRecalls,
            'prev_recalls' => $prevRecalls,
            'widgetName' => $widgetName,
            'recallUtils' => $this->get('app.recall_utils'),
            'widgetState' => $this->getWidgetState($widgetName)->getState(),
        );
    }

    /**
     * @Route("/widget/task", name="dashboard_widget_task", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Dashboard/widgetTaskContents.html.twig")
     */
    public function widgetTaskAction(Request $request)
    {
        $widgetName = 'dashboard_widget_task';

        $this->get('app.task_utils')->generateTasks($this->getUser());

        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $todayQb = $em->getRepository('AppBundle:Task')->createQueryBuilder('r');
        $prevQb = $em->getRepository('AppBundle:Task')->createQueryBuilder('r');

        $todayRecalls = $todayQb
            ->where('r.date = :today')
            ->andWhere('r.date < :tomorrow')
            ->setParameter('today', (new \DateTime())->setTime(0, 0, 0)->format('Y-m-d'))
            ->setParameter('tomorrow', (new \DateTime())->setTime(0, 0, 0)->modify('+1 day')->format('Y-m-d'))
            ->orderBy('r.date', 'DESC')
            ->orderBy('r.completed', 'ASC')
            ->getQuery()->getResult();

        $prevRecalls = $prevQb
            ->where('r.date < :today')
            ->andWhere('r.completed = :false')
            ->setParameter('today', (new \DateTime())->setTime(0, 0, 0)->format('Y-m-d'))
            ->setParameter('false', false)
            ->orderBy('r.date', 'DESC')
            ->getQuery()->getResult();

        return array(
            'today_tasks' => $todayRecalls,
            'prev_tasks' => $prevRecalls,
            'formatter' => $this->get('app.formatter'),
            'widgetName' => $widgetName,
            'widgetState' => $this->getWidgetState($widgetName)->getState(),
        );
    }

    /**
     * @Route("/widget/treatment-note", name="dashboard_widget_treatment_note", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Dashboard/widgetTreatmentNoteContents.html.twig")
     */
    public function widgetTreatmentNoteAction(Request $request)
    {
        $widgetName = 'dashboard_widget_treatment_note';

        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');
        $qb = $em->getRepository('AppBundle:TreatmentNote')->createQueryBuilder('tn')
            ->where('tn.status = :draft')
            ->setParameter('draft', TreatmentNote::STATUS_DRAFT);

        return array(
            'treatmentNotes' => $qb->getQuery()->getResult(),
            'widgetName' => $widgetName,
            'widgetState' => $this->getWidgetState($widgetName)->getState(),
        );
    }

    /**
     * @Route("/widget/state/{widgetName}/{state}", name="dashboard_widget_state", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function widgetStateAction(Request $request, $widgetName, $state)
    {
        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $widgetState = $this->getWidgetState($widgetName);
        $widgetState->setState($state);

        $em->flush();

        return new Response();
    }

    protected function getWidgetState($widgetName)
    {
        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        if ($state = $em->getRepository('AppBundle:WidgetState')->findOneBy(array(
            'name' => $widgetName
        ))) {
            return $state;
        }

        $state = $this->persistWidgetState($widgetName);
        return $state;
    }

    protected function persistWidgetState($widgetName)
    {
        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $state = new WidgetState();
        $state->setName($widgetName)
            ->setState(true);

        $em->persist($state);
        $em->flush();

        return $state;
    }

}
