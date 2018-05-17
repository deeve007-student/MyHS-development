<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:14
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Goal;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Goal controller.
 *
 * Route("goal")
 */
class GoalController extends Controller
{

    /**
     * Creates a new goal entity.
     *
     * @Route("/goal/new", name="goal_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Goal/update.html.twig")
     */
    public function createAction(Request $request)
    {
        $goal = new Goal();

        return $this->update($goal);
    }

    /**
     * Displays a form to edit an existing goal entity.
     *
     * @Route("/goal/{id}/update", name="goal_update", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Goal $goal)
    {
        return $this->update($goal);
    }

    /**
     * @Route("/goal/{id}/check/{state}", name="goal_complete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function completeAction(Request $request, Goal $goal, $state)
    {
        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $goal->setCompleted($state==1 ? true : false);

        $em->flush();

        return new JsonResponse();
    }

    /**
     * Deletes a goal entity.
     *
     * @Route("/goal/{id}/delete", name="goal_delete", options={"expose"=true})
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Goal $goal)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($goal);
        $em->flush();

        $this->addFlash(
            'success',
            'app.goal.message.deleted'
        );

        return new Response('');
    }

    /**
     * @param Goal $entity
     * @return array|JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.goal.form'),
            '@App/Goal/include/form.html.twig',
            $entity,
            'app.goal.message.created',
            'app.goal.message.updated',
            'goal_view',
            $this->get('app.hasher')->encodeObject($entity)
        );
    }

}
