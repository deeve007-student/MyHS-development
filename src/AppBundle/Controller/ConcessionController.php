<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:38
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Concession;
use AppBundle\Utils\FilterUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

/**
 * Concession controller.
 */
class ConcessionController extends Controller
{

    /**
     * Lists all concession entities.
     *
     * @Route("/settings/concession/", name="concession_index", options={"expose"=true})
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('AppBundle:Concession')->createQueryBuilder('c');

        /** @var Router $router */
        $router = $this->get('router');

        return $this->get('app.datagrid_utils')->handleDatagrid(
            $this->get('app.string_filter.form'),
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
            '@App/Concession/include/grid.html.twig',
            $router->generate('concession_index',[],true)
        );
    }

    /**
     * Creates a new concession entity.
     *
     * @Route("/settings/concession/new", name="concession_create")
     * @Method({"GET", "POST"})
     * @Template("@App/Concession/update.html.twig")
     */
    public function createAction(Request $request)
    {
        $concession = $this->get('app.entity_factory')->createConcession();

        return $this->update($concession);
    }

    /**
     * Displays a form to edit an existing concession entity.
     *
     * @Route("/settings/concession/{id}/update", name="concession_update")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Concession $concession)
    {
        return $this->update($concession);
    }

    /**
     * Deletes a concession entity.
     *
     * @Route("/settings/concession/{id}/delete", name="concession_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Concession $concession)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($concession);
            $em->flush();

            $this->addFlash(
                'success',
                'app.concession.message.deleted'
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
            $this->get('app.concession.form'),
            null,
            $entity,
            'app.concession.message.created',
            'app.concession.message.updated',
            'practicioner_settings_index'
        );
    }
}
