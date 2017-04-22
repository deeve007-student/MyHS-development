<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:38
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Patient;
use AppBundle\Entity\TreatmentNote;
use AppBundle\Entity\TreatmentNoteTemplate;
use AppBundle\Utils\FilterUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TreatmentNote controller.
 *
 * Route("treatment_note")
 */
class TreatmentNoteController extends Controller
{


    /**
     * Lists all treatment note entities.
     *
     * @Route("/patient/{id}/treatment-note", name="treatment_note_index")
     * @Method({"GET","POST"})
     * @Template("@App/TreatmentNote/index.html.twig")
     */
    public function indexTreatmentNoteAction(Request $request, Patient $patient)
    {
        $em = $this->getDoctrine()->getManager();

        $tnTemplates = $em->getRepository("AppBundle:TreatmentNoteTemplate")->findAll();

        $qb = $em->getRepository('AppBundle:TreatmentNote')->createQueryBuilder('c')
            ->where('c.patient = :patient')
            ->setParameter('patient', $patient)
            ->orderBy("c.createdAt", "DESC");

        $result = $this->get('app.datagrid_utils')->handleDatagrid(
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
            '@App/TreatmentNote/include/grid.html.twig'
        );

        if (is_array($result)) {
            $result['entity'] = $patient;
            $result['templates'] = $tnTemplates;
        }

        return $result;
    }

    /**
     * Creates a new treatment note entity.
     *
     * @Route("/patient/{patient}/treatment-note/{template}/new", name="treatment_note_create")
     * @Method({"GET", "POST"})
     * @Template("@App/TreatmentNote/update.html.twig")
     *
     * @ParamConverter("patient",class="AppBundle:Patient")
     * @ParamConverter("template",class="AppBundle:TreatmentNoteTemplate")
     */
    public function createTreatmentNoteAction(Patient $patient, TreatmentNoteTemplate $template, Request $request)
    {
        $treatmentNote = $this->get('app.entity_factory')->createTreatmentNote($patient, $template);

        $result = $this->updateTreatmentNote($treatmentNote);
        if (is_array($result)) {
            $result['patient'] = $patient;
        }

        return $result;
    }

    /**
     * Displays a form to edit an existing treatment note entity.
     *
     * @Route("/patient/{patient}/treatment-note/{treatmentNote}/update", name="treatment_note_update")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:TreatmentNote:update.html.twig")
     *
     * @ParamConverter("patient",class="AppBundle:Patient")
     * @ParamConverter("treatmentNote",class="AppBundle:TreatmentNote")
     */
    public function updateTreatmentNoteAction(Patient $patient, TreatmentNote $treatmentNote)
    {
        $result = $this->updateTreatmentNote($treatmentNote);

        if (is_array($result)) {
            $result['patient'] = $patient;
        }

        return $result;
    }

    /**
     * Finds and displays a treatment note entity.
     *
     * @Route("/patient/{patient}/treatment-note/{treatmentNote}", name="treatment_note_view")
     * @Method("GET")
     * @Template()
     *
     * @ParamConverter("patient",class="AppBundle:Patient")
     * @ParamConverter("treatmentNote",class="AppBundle:TreatmentNote")
     */
    public function viewTreatmentNoteAction(Patient $patient, TreatmentNote $treatmentNote)
    {
        return array(
            'entity' => $treatmentNote,
        );
    }

    /**
     * Deletes a treatment note entity.
     *
     * @Route("/patient/{patient}/treatment-note/{treatmentNote}/delete", name="treatment_note_delete")
     * @Method({"DELETE", "GET"})
     *
     * @ParamConverter("patient",class="AppBundle:Patient")
     * @ParamConverter("treatmentNote",class="AppBundle:TreatmentNote")
     */
    public function deleteTreatmentNoteAction(Patient $patient, TreatmentNote $treatmentNote)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($treatmentNote);
        $em->flush();

        $this->addFlash(
            'success',
            'app.treatment_note.message.deleted'
        );

        return $this->redirectToRoute('treatment_note_index');
    }

    /**
     * Treatment note PDF.
     *
     * @Route("/patient/{patient}/treatment-note/{treatmentNote}/pdf", name="treatment_note_pdf")
     * @Template("@App/Invoice/pdf.html.twig")
     * @Method({"GET"})
     *
     * @ParamConverter("patient",class="AppBundle:Patient")
     * @ParamConverter("treatmentNote",class="AppBundle:TreatmentNote")
     */
    public function openPdfAction(Patient $patient, TreatmentNote $treatmentNote)
    {
        $html = $this->renderView(
            '@App/TreatmentNote/pdf.html.twig',
            array(
                'entity' => $treatmentNote,
            )
        );

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'filename="'.$this->generateTreatmentNoteFileName($treatmentNote).'"',
            )
        );
    }

    protected function generateTreatmentNoteFileName(TreatmentNote $treatmentNote)
    {
        return uniqid('invoice_'.$treatmentNote.'_').'.pdf';
    }

    protected function updateTreatmentNote($entity)
    {
        $result = $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.treatment_note.form'),
            $entity,
            'app.treatment_note.message.created',
            'app.treatment_note.message.updated',
            null,
            null,
            function (TreatmentNote $treatmentNote) {
                return $this->redirectToRoute(
                    'treatment_note_index',
                    array(
                        'id' => $this->get('app.hasher')->encodeObject($treatmentNote->getPatient()),
                    )
                );
            }
        );

        return $result;
    }

}
