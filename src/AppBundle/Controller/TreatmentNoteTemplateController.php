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
use AppBundle\Utils\FilterUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

/**
 * TreatmentNoteTemplate controller.
 */
class TreatmentNoteTemplateController extends Controller
{

    /**
     * Lists all treatmentNoteTemplate entities.
     *
     * @Route("/settings/treatment-note-template/", name="treatment_note_template_index", options={"expose"=true})
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('AppBundle:TreatmentNoteTemplate')->createQueryBuilder('tnt');

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
            '@App/TreatmentNoteTemplate/include/grid.html.twig',
            $router->generate('treatment_note_template_index',[],true)
        );
    }

    /**
     * Creates a new treatmentNoteTemplate entity.
     *
     * @Route("/settings/treatment-note-template/new", name="treatment_note_template_create")
     * @Method({"GET", "POST"})
     * @Template("@App/TreatmentNoteTemplate/update.html.twig")
     */
    public function createAction(Request $request)
    {
        $treatmentNoteTemplate = $this->get('app.entity_factory')->createTreatmentNoteTemplate();

        return $this->update($treatmentNoteTemplate);
    }

    /**
     * Displays a form to edit an existing treatmentNoteTemplate entity.
     *
     * @Route("/settings/treatment-note-template/{id}/update", name="treatment_note_template_update")
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
     * @Route("/settings/treatment-note-template/{id}/delete", name="treatment_note_template_delete")
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
            null,
            $entity,
            'app.treatment_note_template.message.created',
            'app.treatment_note_template.message.updated',
            'practicioner_settings_index'
        );
    }
}
