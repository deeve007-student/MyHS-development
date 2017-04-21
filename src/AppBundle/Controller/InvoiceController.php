<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:14
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\Patient;
use AppBundle\Utils\FilterUtils;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Invoice controller.
 *
 * Route("invoice")
 */
class InvoiceController extends Controller
{

    /**
     * Lists all invoice entities.
     *
     * @Route("/invoice/", name="invoice_index")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->getRepository('AppBundle:Invoice')->createQueryBuilder('i');
        $qb->leftJoin('i.patient','p')
        ->orderBy('i.date','DESC');

        return $this->get('app.datagrid_utils')->handleDatagrid(
            $this->get('app.invoice_filter.form'),
            $request,
            $qb,
            function ($qb, $filterData) {
                FilterUtils::buildTextGreedyCondition(
                    $qb,
                    array(
                        'name',
                        'p.title',
                        'p.firstName',
                        'p.lastName',
                    ),
                    $filterData['string']
                );
            },
            '@App/Invoice/include/grid.html.twig'
        );
    }

    /**
     * Lists all patients invoices.
     *
     * @Route("/patient/{id}/invoice", name="patient_invoice_index")
     * @Method({"GET","POST"})
     * @Template("@App/Invoice/indexPatient.html.twig")
     */
    public function indexInvoiceAction(Request $request, Patient $patient)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('AppBundle:Invoice')
            ->createQueryBuilder('i')
            ->where('i.patient = :patient')
            ->setParameter('patient', $patient)
            ->leftJoin('i.patient', 'p')
            ->orderBy('i.date', 'DESC');

        $result = $this->get('app.datagrid_utils')->handleDatagrid(
            null,
            $request,
            $qb,
            null,
            '@App/Invoice/include/grid.html.twig'
        );

        if (is_array($result)) {
            $result['entity'] = $patient;
        }

        return $result;
    }

    /**
     * Creates a new invoice entity.
     *
     * @Route("/invoice/new", name="invoice_create")
     * @Method({"GET", "POST"})
     * @Template("@App/Invoice/update.html.twig")
     */
    public function createAction(Request $request)
    {
        $invoice = $this->get('app.entity_factory')->createInvoice();

        return $this->update($invoice);
    }

    /**
     * Creates a new patient invoice entity.
     *
     * @Route("/patient/{patient}/invoice/new", name="patient_invoice_create")
     * @Method({"GET", "POST"})
     * @Template("@App/Invoice/update.html.twig")
     */
    public function createFromPatientAction(Patient $patient)
    {
        $invoice = $this->get('app.entity_factory')->createInvoice($patient);

        $result = $this->update($invoice);

        if (is_array($result)) {
            $result['backToPatient'] = true;
        }

        return $result;
    }

    /**
     * Finds and displays a invoice entity.
     *
     * @Route("/invoice/{id}", name="invoice_view")
     * @Method("GET")
     * @Template()
     */
    public function viewAction(Invoice $invoice)
    {
        return array(
            'entity' => $invoice,
        );
    }

    /**
     * Changes invoice status.
     *
     * @Route("/invoice/{id}/status/{status}", name="invoice_status_update")
     * @Method("GET")
     */
    public function statusAction(Invoice $invoice, $status)
    {
        if ($this->get('app.entity_factory')->updateInvoiceStatus($invoice, $status)) {
            $this->addFlash(
                'success',
                'app.invoice.message.status_changed'
            );
        } else {
            $this->addFlash(
                'danger',
                'app.invoice.message.status_no_items'
            );
        }

        return $this->redirectToRoute('invoice_view', array('id' => $this->get('app.hasher')->encodeObject($invoice)));
    }

    /**
     * Duplicates invoice.
     *
     * @Route("/invoice/{id}/duplicate", name="invoice_duplicate")
     * @Method("GET")
     */
    public function duplicateAction(Invoice $invoice)
    {
        $duplicate = $this->get('app.entity_factory')->duplicateInvoice($invoice);

        $this->addFlash(
            'success',
            'app.invoice.message.duplicated'
        );

        return $this->redirectToRoute('invoice_view', array('id' => $this->get('app.hasher')->encodeObject($duplicate)));
    }

    /**
     * Displays a form to edit an existing invoice entity.
     *
     * @Route("/invoice/{id}/update", name="invoice_update")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Invoice $invoice)
    {
        return $this->update($invoice);
    }

    /**
     * Deletes a invoice entity.
     *
     * @Route("/invoice/{id}/delete", name="invoice_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Invoice $invoice)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($invoice);
        $em->flush();

        $this->addFlash(
            'success',
            'app.invoice.message.deleted'
        );

        return $this->redirectToRoute('invoice_index');
    }

    /**
     * Sends invoice PDF to client.
     *
     * @Route("/invoice/{id}/pdf-send", name="invoice_pdf_send", options={"expose"=true})
     * @Method({"GET","POST"})
     */
    public function sendPdfAction(Request $request, Invoice $invoice)
    {
        $this->checkInvoiceNotDraft($invoice);

        $mailer = $this->get('app.mailer');
        $patient = $invoice->getPatient();
        $tempInvoice = $this->generateInvoiceTempFile($invoice);

        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                '@App/Invoice/pdf.html.twig',
                array(
                    'entity' => $invoice,
                )
            ),
            $tempInvoice
        );

        if ($patient->getEmail()) {
            $body = $this->renderView(
                '@App/Invoice/email.html.twig',
                array(
                    'patient' => $patient,
                )
            );

            $message = $mailer->createPracticionerMessage($patient->getOwner())
                ->setSubject(
                    'Invoice '.$invoice.' issued at '.$this->get('app.formatter')->formatDate($invoice->getDate())
                )
                ->setTo($patient->getEmail(), (string)$patient)
                ->setBody($body);

            $message->attach(\Swift_Attachment::fromPath($tempInvoice));
            $mailer->send($message, true);

            $result = array(
                'error' => 0,
                'message' => 'app.invoice.message.email_pdf_sent',
            );
        } else {
            $result = array(
                'error' => 1,
                'message' => 'app.invoice.message.email_pdf_blank_email',
            );
        }

        unlink($tempInvoice);

        return new JsonResponse(json_encode($result));
    }

    /**
     * Invoice PDF.
     *
     * @Route("/invoice/{id}/pdf", name="invoice_pdf")
     * @Template("@App/Invoice/pdf.html.twig")
     * @Method({"GET"})
     */
    public function openPdfAction(Request $request, Invoice $invoice)
    {
        $this->checkInvoiceNotDraft($invoice);

        $html = $this->renderView(
            '@App/Invoice/pdf.html.twig',
            array(
                'entity' => $invoice,
            )
        );

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'filename="'.$this->generateInvoiceFileName($invoice).'"',
            )
        );
    }

    protected function checkInvoiceNotDraft(Invoice $invoice)
    {
        if ($invoice->isDraft()) {
            throw new \Exception('Available only for non-draft invoices');
        }
    }

    protected function generateInvoiceFileName(Invoice $invoice)
    {
        return uniqid('invoice_'.$invoice.'_').'.pdf';
    }

    protected function generateInvoiceTempFile(Invoice $invoice)
    {
        return $this->getParameter('kernel.root_dir').'/../temp/'.$this->generateInvoiceFileName($invoice);
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.invoice.form'),
            $entity,
            'app.invoice.message.created',
            'app.invoice.message.updated',
            'invoice_view',
            $this->get('app.hasher')->encodeObject($entity)
        );
    }

}
