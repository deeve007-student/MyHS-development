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
 * @Route("invoice")
 */
class InvoiceController extends Controller
{

    /**
     * Lists all invoice entities.
     *
     * @Route("/", name="invoice_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('AppBundle:Invoice')
            ->createQueryBuilder('i')
            ->getQuery();

        $paginator = $this->get('knp_paginator');
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
     * Creates a new invoice entity.
     *
     * @Route("/new", name="invoice_create")
     * @Method({"GET", "POST"})
     * @Template("@App/Invoice/update.html.twig")
     */
    public function createAction(Request $request)
    {
        $invoice = $this->get('app.entity_factory')->createInvoice();

        return $this->update($invoice);
    }

    /**
     * Finds and displays a invoice entity.
     *
     * @Route("/{id}", name="invoice_view")
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
     * @Route("/{id}/status/{status}", name="invoice_status_update")
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

        return $this->redirectToRoute('invoice_view', array('id' => $invoice->getId()));
    }

    /**
     * Duplicates invoice.
     *
     * @Route("/{id}/duplicate", name="invoice_duplicate")
     * @Method("GET")
     */
    public function duplicateAction(Invoice $invoice)
    {
        $duplicate = $this->get('app.entity_factory')->duplicateInvoice($invoice);

        $this->addFlash(
            'success',
            'app.invoice.message.duplicated'
        );

        return $this->redirectToRoute('invoice_view', array('id' => $duplicate->getId()));
    }

    /**
     * Displays a form to edit an existing invoice entity.
     *
     * @Route("/{id}/update", name="invoice_update")
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
     * @Route("/{id}/delete", name="invoice_delete")
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
     * @Route("/{id}/pdf-send", name="invoice_pdf_send", options={"expose"=true})
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

        $result = array();
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
     * @Route("/{id}/pdf", name="invoice_pdf")
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
            $entity->getId()
        );
    }

}
