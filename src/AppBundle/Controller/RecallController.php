<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:14
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Recall;
use AppBundle\Entity\MessageLog;
use AppBundle\Entity\Patient;
use AppBundle\Utils\FilterUtils;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("/patient/{id}/recall", name="patient_recall_index")
     * @Method({"GET","POST"})
     * @Template("@App/Recall/indexPatient.html.twig")
     */
    public function indexPatientAction(Request $request, Patient $patient)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('AppBundle:Recall')
            ->createQueryBuilder('i')
            ->where('i.patient = :patient')
            ->setParameter('patient', $patient)
            ->leftJoin('i.patient', 'p')
            ->leftJoin('i.recallType', 'rt')
            ->leftJoin('i.recallFor', 'rf')
            ->orderBy('i.date', 'DESC');

        $result = $this->filterRecalls($request, $qb);

        if (is_array($result)) {
            $result['entity'] = $patient;
        }

        return $result;
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
     * @Route("/recall/{id}/check/{state}", name="recall_complete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function completeAction(Request $request, Recall $task, $state)
    {
        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $task->setCompleted($state==1 ? true : false);

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
        return $this->update($recall);
    }

    /**
     * Deletes a recall entity.
     *
     * @Route("/recall/{id}/delete", name="recall_delete", options={"expose"=true})
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Recall $recall)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($recall);
        $em->flush();

        $this->addFlash(
            'success',
            'app.recall.message.deleted'
        );

        return $this->redirectToRoute('recall_index');
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.recall.form'),
            '@App/Recall/include/form.html.twig',
            $entity,
            'app.recall.message.created',
            'app.recall.message.updated',
            'recall_view',
            $this->get('app.hasher')->encodeObject($entity)
        );
    }

}
