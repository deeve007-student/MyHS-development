<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:38
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Patient;
use AppBundle\Entity\TreatmentNote;
use AppBundle\Entity\TreatmentNoteTemplate;
use AppBundle\Form\Type\TreatmentNoteExportType;
use AppBundle\Utils\DateTimeUtils;
use AppBundle\Utils\FilterUtils;
use Doctrine\ORM\QueryBuilder;
use ReportBundle\Form\Type\DateRangeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\VarDumper\VarDumper;

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
    public function indexAction(Request $request, Patient $patient)
    {
        $result = $this->getIndexData($request, $patient);
        return $result;
    }

    /**
     * Lists all treatment note entities from appointment.
     *
     * @Route("/patient/{id}/appointment-treatment-note/{appointment}", name="appointment_treatment_note_index")
     * @Method({"GET","POST"})
     * @Template("@App/TreatmentNote/index.html.twig")
     */
    public function indexFromAppointmentAction(Request $request, Patient $patient, Appointment $appointment)
    {
        $result = $this->getIndexData($request, $patient);
        $result['appointment'] = $appointment;
        return $result;
    }

    protected function getIndexData(Request $request, Patient $patient)
    {
        $em = $this->getDoctrine()->getManager();

        $tnTemplates = $em->getRepository("AppBundle:TreatmentNoteTemplate")->findAll();
        $tnDefaultTemplate = $em->getRepository("AppBundle:TreatmentNoteTemplate")->findOneBy(
            array(
                'default' => true,
            )
        );

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
            $result['defaultTemplate'] = $tnDefaultTemplate;
        }

        return $result;
    }

    /**
     * Creates a new treatment note entity.
     *
     * @Route("/patient/{patient}/treatment-note/{template}/new", name="treatment_note_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/TreatmentNote/update.html.twig")
     *
     * @ParamConverter("patient",class="AppBundle:Patient")
     * @ParamConverter("template",class="AppBundle:TreatmentNoteTemplate")
     */
    public function createAction(Patient $patient, TreatmentNoteTemplate $template, Request $request)
    {

        $treatmentNote = $this->get('app.entity_factory')->createTreatmentNote($patient, $template);

        $result = $this->update($treatmentNote);
        if (is_array($result)) {
            $result['patient'] = $patient;
        }

        return $result;
    }

    /**
     * Creates a new treatment note entity from previous note
     *
     * @Route("/patient/{patient}/treatment-note/copy", name="treatment_note_copy", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/TreatmentNote/update.html.twig")
     *
     * @ParamConverter("patient",class="AppBundle:Patient")
     */
    public function copyAction(Patient $patient, Request $request)
    {

        if ($treatmentNote = $this->get('app.treatment_note_utils')->getLastFinalNoteByPatient($patient)) {
            $treatmentNoteCopy = clone $treatmentNote;
            //VarDumper::dump($treatmentNote);
            //VarDumper::dump($treatmentNoteCopy);
            //die();
            $result = $this->update($treatmentNoteCopy);
            if (is_array($result)) {
                $result['patient'] = $patient;
            }
            return $result;
        }

        throw new NotFoundHttpException('Previous finalized treatment note not found');

    }

    /**
     * Creates a new treatment note entity from appointment.
     *
     * @Route("/patient/{patient}/treatment-note/{template}/new/appointment/{appointment}", name="appointment_treatment_note_create")
     * @Method({"GET", "POST"})
     * @Template("@App/TreatmentNote/update.html.twig")
     */
    public function createFromAppointmentAction(Patient $patient, TreatmentNoteTemplate $template, Appointment $appointment)
    {
        $treatmentNote = $this->get('app.entity_factory')->createTreatmentNote($patient, $template);

        $treatmentNote->setAppointment($appointment);
        $treatmentNote->setCreatedAt($appointment->getStart());

        $result = $this->update($treatmentNote);

        if (is_array($result)) {
            $result['patient'] = $patient;
        }

        return $result;
    }

    /**
     * Displays modal export form
     *
     * @Route("/patient/{patient}/treatment-note/export", name="treatment_note_export_modal", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @ParamConverter("patient",class="AppBundle:Patient")
     */
    public function exportModalAction(Request $request, Patient $patient)
    {
        $form = $this->get('app.treatment_note_export.form');

        $form->setData($patient);

        $data = $this->get('twig')->render(
            "AppBundle:TreatmentNote/include:exportForm.html.twig",
            array(
                'entity' => $patient,
                'form' => $form->createView(),
            )
        );

        return new JsonResponse(json_encode(array(
            'form' => $data,
        )));
    }

    /**
     * Returns treatment notes py range passed
     *
     * @Route("/patient/{patient}/treatment-note/export/filter", name="treatment_note_export_filter", options={"expose"=true})
     * @Method({"POST"})
     *
     * @ParamConverter("patient",class="AppBundle:Patient")
     */
    public function filterModalAction(Request $request, Patient $patient)
    {
        $range = $request->get('app_treatment_note_export')['range'];
        $dateStart = $request->get('app_treatment_note_export')['dateStart'];
        $dateEnd = $request->get('app_treatment_note_export')['dateEnd'];

        /** @var QueryBuilder $notesQb */
        $notesQb = $this->getDoctrine()->getManager()->getRepository('AppBundle:TreatmentNote')->createQueryBuilder('n');
        $notesQb->orderBy('n.createdAt', 'DESC');

        if ($range == DateRangeType::LAST) {
            $notesQb->setMaxResults(5);
        } elseif ($range == 'range') {
            $dateStart = \DateTime::createFromFormat($this->get('app.formatter')->getBackendDateFormat(), $dateStart);
            $dateEnd = \DateTime::createFromFormat($this->get('app.formatter')->getBackendDateFormat(), $dateEnd);
            $dateStart = DateTimeUtils::getDate($dateStart)->setTimezone(new \DateTimeZone('UTC'));
            $dateEnd = DateTimeUtils::getDate($dateEnd)->setTime(23, 59, 59);

            $notesQb->andWhere('n.createdAt >= :start')
                ->andWhere('n.createdAt <= :end')
                ->setParameters(array(
                    'start' => $dateStart,
                    'end' => $dateEnd,
                ));
        } else {
            list($dateStart, $dateEnd) = DateRangeType::getRangeDates($range);

            $notesQb->andWhere('n.createdAt >= :start')
                ->andWhere('n.createdAt <= :end')
                ->setParameters(array(
                    'start' => $dateStart,
                    'end' => $dateEnd,
                ));
        }

        $notes = $notesQb->getQuery()->getResult();

        $result = array();
        foreach ($notes as $note) {
            $result[] = array(
                'id' => $this->get('app.hasher')->encodeObject($note),
                'name' => $this->get('app.treatment_note.twig.extension')->treatmentNoteName($note),
            );
        }

        return new JsonResponse($result);
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
    public function updateAction(Patient $patient, TreatmentNote $treatmentNote)
    {
        $result = $this->update($treatmentNote);

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
     * @Template("@App/TreatmentNote/index.html.twig")
     *
     * @ParamConverter("patient",class="AppBundle:Patient")
     * @ParamConverter("treatmentNote",class="AppBundle:TreatmentNote")
     */
    public function view(Request $request, Patient $patient, TreatmentNote $treatmentNote)
    {
        $result = $this->indexAction($request, $patient);

        $result['entities'] = array($treatmentNote);

        return $result;
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
    public function delete(Patient $patient, TreatmentNote $treatmentNote)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($treatmentNote);
        $em->flush();

        $this->addFlash(
            'success',
            'app.treatment_note.message.deleted'
        );

        return $this->redirectToRoute(
            'treatment_note_index',
            array(
                'id' => $this->get('app.hasher')->encodeObject($patient),
            )
        );
    }

    /**
     * Treatment note PDF.
     *
     * @Route("/patient/{patient}/treatment-note/{treatmentNote}/pdf", name="treatment_note_pdf")
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
                'entities' => array($treatmentNote),
            )
        );

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'filename="' . $this->generateTreatmentNoteFileName($treatmentNote) . '"',
            )
        );
    }

    /**
     * Treatment notes PDF.
     *
     * @Route("/patient/{patient}/export-treatment-note", name="treatment_notes_pdf", options={"expose"=true})
     * @Method({"GET"})
     *
     * @ParamConverter("patient",class="AppBundle:Patient")
     */
    public function exportMassAction(Request $request, Patient $patient)
    {
        $notes = array();
        $ids = explode(',', $request->get('notes'));

        foreach ($ids as $id) {
            $trueId = $this->get('app.hasher')->decode($id, TreatmentNote::class);
            $notes[] = $this->getDoctrine()->getManager()->getRepository('AppBundle:TreatmentNote')->find($trueId);
        }

        $html = $this->renderView(
            '@App/TreatmentNote/pdf.html.twig',
            array(
                'entities' => $notes,
            )
        );

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'filename="treatment_notes_' . md5(microtime()) . '"',
            )
        );
    }

    protected function generateTreatmentNoteFileName(TreatmentNote $treatmentNote)
    {
        return uniqid('treatment_note_' . $treatmentNote . '_') . '.pdf';
    }

    protected function update($entity)
    {
        $result = $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.treatment_note.form'),
            '@App/TreatmentNote/include/form.html.twig',
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
