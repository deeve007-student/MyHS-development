<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:38
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Concession;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Concession controller.
 *
 * @Route("concession")
 */
class ConcessionController extends Controller
{

    /**
     * Lists all concession entities.
     *
     * @Route("/", name="concession_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $concessions = $em->getRepository('AppBundle:Concession')->findAll();

        $paginator  = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $concessions,
            $request->query->getInt('page', 1),
            self::ITEMS_PER_PAGE
        );

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new concession entity.
     *
     * @Route("/new", name="concession_create")
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
     * @Route("/{id}/update", name="concession_update")
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
     * @Route("/{id}/delete", name="concession_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Concession $concession)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($concession);
        $em->flush();

        $this->addFlash(
            'success',
            'app.concession.message.deleted'
        );

        return $this->redirectToRoute('concession_index');
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.concession.form'),
            $entity,
            'app.concession.message.created',
            'app.concession.message.updated',
            'concession_index',
            $this->get('app.hasher')->encodeObject($entity)
        );
    }
}