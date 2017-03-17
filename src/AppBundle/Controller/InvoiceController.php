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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $invoices = $em->getRepository('AppBundle:Invoice')->findAll();

        return array(
            'invoices' => $invoices,
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
     * Creates a new invoice entity.
     *
     * @Route("/new/{id}", name="invoice_create_from_patient")
     * @Method({"GET", "POST"})
     * @Template("@App/Invoice/update.html.twig")
     */
    public function createFromPatientAction(Patient $patient)
    {
        $invoice = $this->get('app.entity_factory')->createInvoice($patient);

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
        if (!in_array($status, $invoice->getAvailableStatuses())) {
            throw new AccessDeniedHttpException();
        }

        if ($invoice->getItems()->count() > 0) {
            $invoice->setStatus($status);
            $this->getDoctrine()->getManager()->flush();

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
        $invNumber = $this->get('app.entity_factory')->generateNewInvoiceNumber();

        $duplicate = clone $invoice;
        $duplicate->setName($invNumber);
        $this->getDoctrine()->getManager()->persist($duplicate);
        $this->getDoctrine()->getManager()->flush();

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
