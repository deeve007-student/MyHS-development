<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:14
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\RecurringTask;
use AppBundle\Entity\Task;
use AppBundle\Entity\MessageLog;
use AppBundle\Entity\Patient;
use AppBundle\Utils\FilterUtils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Task controller.
 *
 * Route("task")
 */
class RecurringTaskController extends Controller
{

    /**
     * Lists all task entities.
     *
     * @Route("/task", name="task_index")
     * @Method({"GET","POST"})
     * @Template("@App/Task/index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->getRepository('AppBundle:RecurringTask')->createQueryBuilder('t');
        $qb->orderBy('t.createdAt', 'DESC');

        return $this->filterInvoices($request, $qb);
    }

    protected function filterInvoices(Request $request, QueryBuilder $qb)
    {
        return $this->get('app.datagrid_utils')->handleDatagrid(
            $this->get('app.string_filter.form'),
            $request,
            $qb,
            function (QueryBuilder $qb, $filterData) {
                FilterUtils::buildTextGreedyCondition(
                    $qb,
                    array(
                        'text',
                    ),
                    $filterData['string']
                );
            },
            '@App/Task/include/grid.html.twig'
        );
    }

    /**
     * Creates a new task entity.
     *
     * @Route("/task/new", name="task_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Task/update.html.twig")
     */
    public function createAction(Request $request)
    {
        $task = $this->get('app.entity_factory')->createTask();

        return $this->update($task);
    }

    /**
     * Displays a form to edit an existing task entity.
     *
     * @Route("/task/{id}/update", name="task_update", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, RecurringTask $task)
    {
        return $this->update($task);
    }

    /**
     * @Route("/task/{id}/check/{state}", name="task_complete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function completeAction(Request $request, Task $task, $state)
    {
        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $task->setCompleted($state==1 ? true : false);

        $em->flush();

        return new JsonResponse();
    }

    /**
     * Deletes a task entity.
     *
     * @Route("/task/{id}/delete", name="task_delete", options={"expose"=true})
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, RecurringTask $task)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash(
            'success',
            'app.task.message.deleted'
        );

        return $this->redirectToRoute('task_index');
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.recurring_task.form'),
            '@App/Task/include/form.html.twig',
            $entity,
            'app.task.message.created',
            'app.task.message.updated',
            'task_view',
            $this->get('app.hasher')->encodeObject($entity)
        );
    }

}
