<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:14
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\Recall;
use AppBundle\Entity\Patient;
use AppBundle\Entity\RecallType;
use AppBundle\Utils\FilterUtils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Recall controller.
 *
 * Route("recall")
 */
class RecallController extends Controller
{

    /**
     * Lists all patients recalls.
     *
     * @Route("/patient/{id}/recalls-new", defaults={"openNewWindow"=true}, name="patient_recall_index_with_new")
     * @Route("/patient/{id}/recall", defaults={"openNewWindow"=null}, name="patient_recall_index")
     * @Method({"GET","POST"})
     * @Template("@App/Recall/indexPatient.html.twig")
     */
    public function indexPatientAction(Request $request, Patient $patient, $openNewWindow)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->getRepository('AppBundle:Recall')
            ->createQueryBuilder('i')
            ->where('i.patient = :patient')
            ->setParameter('patient', $patient)
            ->leftJoin('i.patient', 'p')
            ->leftJoin('i.recallType', 'rt')
            ->leftJoin('i.recallFor', 'rf')
            ->orderBy('i.date', 'DESC');

        $qb->andWhere($qb->expr()->isNull('i.completed'));

        $result = $this->filterRecalls($request, $qb);

        if ($request->query->has('skip')) {
            $openNewWindow = null;
        }

        if (is_array($result)) {
            $result['entity'] = $patient;
            $result['create'] = $openNewWindow;
            $result['recallUtils'] = $this->get('app.recall_utils');
        }

        return $result;
    }

    /**
     * Returns prev and next patient recalls
     *
     * @Route("/patient-widget/{id}", name="patient_recall_widget", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function patientWidgetAction(Patient $patient)
    {
        $recallUtils = $this->get('app.recall_utils');

        $nextRecall = null;
        $prevRecall = null;

        $nextRecalls = $recallUtils->getNextRecallsByPatientQb($patient)->getQuery()->getResult();
        $prevRecalls = $recallUtils->getPrevRecallsByPatientQb($patient)->getQuery()->getResult();

        if ($nextRecalls && isset($nextRecalls[0])) {
            $nextRecall = $nextRecalls[0];
        }

        if ($prevRecalls && isset($prevRecalls[0])) {
            $prevRecall = $prevRecalls[0];
        }

        return array(
            'patient' => $patient,
            'nextRecall' => $nextRecall,
            'prevRecall' => $prevRecall,
            'eventClass' => Recall::class,
        );
    }

    protected function filterRecalls(Request $request, QueryBuilder $qb)
    {
        return $this->get('app.datagrid_utils')->handleDatagrid(
            $this->get('app.string_filter.form'),
            $request,
            $qb,
            function (QueryBuilder $qb, $filterData) {
                FilterUtils::buildTextGreedyCondition(
                    $qb,
                    array(
                        'i.text',
                        'rf.name',
                        'rt.name',
                    ),
                    $filterData['string']
                );
            },
            '@App/Recall/include/grid.html.twig'
        );
    }

    /**
     * Change recall type via selector
     *
     * @Route("/recall/{recall}/change-type/{type}", name="recall_change_type", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function changeTypeAction(Request $request, Recall $recall, RecallType $type)
    {
        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $recall->setRecallType($type);
        $em->flush();

        return new JsonResponse();
    }

    /**
     * @Route("/recall/{id}/check/{state}", name="recall_complete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function completeAction(Request $request, Recall $task, $state)
    {
        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $task->setCompleted($state == 1 ? true : false);

        $em->flush();

        return new JsonResponse();
    }

    /**
     * Creates a new recall entity.
     *
     * @Route("/recall/new", name="recall_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Recall/update.html.twig")
     */
    public function createAction(Request $request)
    {
        $recall = $this->get('app.entity_factory')->createRecall();

        return $this->update($recall);
    }

    /**
     * Creates a new patient recall entity.
     *
     * @Route("/patient/{patient}/recall/new", name="patient_recall_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Recall/update.html.twig")
     */
    public function createFromPatientAction(Patient $patient)
    {
        $recall = $this->get('app.entity_factory')->createRecall($patient);

        $result = $this->update($recall);

        if (is_array($result)) {
            $result['backToPatient'] = true;
        }

        return $result;
    }

    /**
     * Displays a form to edit an existing recall entity.
     *
     * @Route("/recall/{id}/update", name="recall_update", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Recall $recall)
    {
        $result = $this->update($recall, array(
            'type' => $recall->getRecallType()->getName()
        ));

        $form = $this->get('app.recall.form');
        if ($form->isSubmitted() && $form->isValid()) {
            if ($recall->getId()) {
                $em = $this->getDoctrine()->getManager();
                $recall->setCompleted(true);

                $notificator = $this->get('app.notificator');

                if ($recall->getRecallType()->isByEmail()) {
                    $message = new Message(Message::TYPE_EMAIL);
                    $message->setTag(Message::TAG_RECALL)
                        ->setRecipient($recall->getPatient())
                        ->setSubject($recall->getSubject())
                        ->setBodyData($recall->getMessage());

                    $notificator->sendMessage($message);
                }

                if ($recall->getRecallType()->isBySms()) {
                    $message = new Message(Message::TYPE_SMS);
                    $message->setTag(Message::TAG_RECALL)
                        ->setRecipient($recall->getPatient())
                        ->setSubject($recall->getSubject())
                        ->setBodyData($recall->getSms());

                    $notificator->sendMessage($message);
                }

                if ($recall->getRecallType()->isByCall()) {
                    $message = new Message(Message::TYPE_CALL);
                    $message->setTag(Message::TAG_RECALL)
                        ->setRecipient($recall->getPatient());

                    $notificator->sendMessage($message);
                }

                $em->flush();
            }
        }

        return $result;
    }

    /**
     * Unsets recall type (if submission was canceled)
     *
     * @Route("/recall/{id}/reset-type", name="recall_reset_type", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function resetTypeAction(Request $request, Recall $recall)
    {
        $em = $this->getDoctrine()->getManager();
        $recall->setRecallType(null);
        $em->flush();

        return new JsonResponse();
    }

    /**
     * Finds and displays recall
     *
     * @Route("/recall/{id}", name="recall_view", options={"expose"=true})
     * @Method("GET")
     * @Template("@App/Recall/include/modalView.html.twig")
     */
    public function viewAction(Recall $recall)
    {
        return array(
            'entity' => $recall,
        );
    }

    /**
     * Deletes a recall entity.
     *
     * @Route("/recall/{id}/delete", name="recall_delete", options={"expose"=true})
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Recall $recall)
    {
        $patient = $recall->getPatient();

        $em = $this->getDoctrine()->getManager();
        $em->remove($recall);
        $em->flush();

        $this->addFlash(
            'success',
            'app.recall.message.deleted'
        );

        return $this->redirectToRoute('patient_recall_index', array(
            'id' => $this->get('app.hasher')->encodeObject($patient),
        ));
    }

    protected function update($entity, $additionalData = array())
    {
        $form = $this->get('app.recall_new.form');
        if ($entity->getId()) {
            $form = $this->get('app.recall.form');
        }
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $form,
            '@App/Recall/include/form.html.twig',
            $entity,
            'app.recall.message.created',
            'app.recall.message.updated',
            'recall_view',
            $this->get('app.hasher')->encodeObject($entity),
            null,
            $additionalData
        );
    }

}
