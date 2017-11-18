<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:38
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Concession;
use AppBundle\Entity\RecallFor;
use AppBundle\Utils\FilterUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

/**
 * RecallFor controller.
 */
class RecallForController extends Controller
{

    /**
     * Lists all concession entities.
     *
     * @Route("/settings/recall-reason/", name="recall_for_index", options={"expose"=true})
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('AppBundle:RecallFor')->createQueryBuilder('c');

        /** @var Router $router */
        $router = $this->get('router');

        return $this->get('app.datagrid_utils')->handleDatagrid(
            null,//$this->get('app.string_filter.form'),
            $request,
            $qb,
            function ($qb, $filterData) {
                FilterUtils::buildTextGreedyCondition(
                    $qb,
                    array(
                        'name',
                    ),
                    $filterData['string']
                );
            },
            '@App/RecallFor/include/grid.html.twig',
            $router->generate('recall_for_index',[],true)
        );
    }

    /**
     * Creates a new concession entity.
     *
     * @Route("/settings/recall-reason/new", name="recall_for_create")
     * @Method({"GET", "POST"})
     * @Template("@App/Concession/update.html.twig")
     */
    public function createAction(Request $request)
    {
        $recallFor = $this->get('app.entity_factory')->createRecallFor();

        return $this->update($recallFor);
    }

    /**
     * Displays a form to edit an existing concession entity.
     *
     * @Route("/settings/recall-reason/{id}/update", name="recall_for_update")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, RecallFor $recallFor)
    {
        return $this->update($recallFor);
    }

    /**
     * Deletes a concession entity.
     *
     * @Route("/settings/recall-reason/{id}/delete", name="recall_for_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, RecallFor $recallFor)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($recallFor);
            $em->flush();

            $this->addFlash(
                'success',
                'app.recall_for.message.deleted'
            );
        } catch (\Exception $exception) {
            $this->addFlash(
                'danger',
                'app.message.undefined_error'
            );
        }

        return $this->redirectToRoute('practicioner_settings_index');
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.recall_for.form'),
            null,
            $entity,
            'app.recall_for.message.created',
            'app.recall_for.message.updated',
            'practicioner_settings_index'
        );
    }
}
