<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 19:46
 */

namespace AppBundle\Controller;

use AppBundle\Entity\TreatmentNoteTemplate;
use AppBundle\Form\Type\TreatmentNoteTemplateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TreatmentNoteTemplate controller.
 *
 * @Route("treatment-note-template")
 */
class TreatmentNoteTemplateController extends Controller
{

    /**
     * Lists all treatmentNoteTemplate entities.
     *
     * @Route("/", name="treatment_note_template_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('AppBundle:TreatmentNoteTemplate')
            ->createQueryBuilder('t')
            ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            self::ITEMS_PER_PAGE
        );

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new treatmentNoteTemplate entity.
     *
     * @Route("/new", name="treatment_note_template_create")
     * @Method({"GET", "POST"})
     * @Template("@App/TreatmentNoteTemplate/update.html.twig")
     */
    public function createAction(Request $request)
    {
        $treatmentNoteTemplate = $this->get('app.entity_factory')->createTreatmentNoteTemplate();

        return $this->update($treatmentNoteTemplate);
    }

    /**
     * Finds and displays a treatmentNoteTemplate entity.
     *
     * @Route("/{id}", name="treatment_note_template_view")
     * @Method("GET")
     * @Template()
     */
    public function viewAction(TreatmentNoteTemplate $treatmentNoteTemplate)
    {
        return array(
            'entity' => $treatmentNoteTemplate,
        );
    }

    /**
     * Displays a form to edit an existing treatmentNoteTemplate entity.
     *
     * @Route("/{id}/update", name="treatment_note_template_update")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, TreatmentNoteTemplate $treatmentNoteTemplate)
    {
        return $this->update($treatmentNoteTemplate);
    }

    /**
     * Deletes a treatmentNoteTemplate entity.
     *
     * @Route("/{id}/delete", name="treatment_note_template_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, TreatmentNoteTemplate $treatmentNoteTemplate)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($treatmentNoteTemplate);
        $em->flush();

        $this->addFlash(
            'success',
            'app.treatment_note_template.message.deleted'
        );

        return $this->redirectToRoute('treatment_note_template_index');
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.treatment_note_template.form'),
            $entity,
            'app.treatment_note_template.message.created',
            'app.treatment_note_template.message.updated',
            'treatment_note_template_view',
            $entity->getId()
        );
    }
}
