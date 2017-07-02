<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:38
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoicePayment;
use AppBundle\Utils\FilterUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * InvoicePayment controller.
 *
 * Route("invoice-payment")
 */
class InvoicePaymentController extends Controller
{

    /**
     * Lists all invoice payment entities.
     *
     * @Route("/{id}", name="invoice_payment_index", options={"expose"=true})
     * @Method({"GET","POST"})
     * @Template("@App/InvoicePayment/include/list.html.twig")
     */
    public function indexAction(Request $request, Invoice $invoice)
    {
        return array(
            'entity' => $invoice,
        );
    }

    /**
     * Creates a new invoice payment entity.
     *
     * @Route("/new/{id}", name="invoice_payment_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/InvoicePayment/update.html.twig")
     */
    public function createAction(Request $request, Invoice $invoice)
    {
        $invoicePayment = $this->get('app.entity_factory')->createInvoicePayment($invoice);

        return $this->update($invoicePayment);
    }

    /**
     * Displays a form to edit an existing invoice payment entity.
     *
     * @Route("/{id}/update", name="invoice_payment_update")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, InvoicePayment $invoicePayment)
    {
        return $this->update($invoicePayment);
    }

    /**
     * Deletes a invoice payment entity.
     *
     * @Route("/{id}/delete", name="invoice_payment_delete", options={"expose"=true})
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, InvoicePayment $invoicePayment)
    {
        $em = $this->getDoctrine()->getManager();
        $invoiceId = $this->get('app.hasher')->encodeObject($invoicePayment->getInvoice());

        $em->remove($invoicePayment);
        $em->flush();

        $this->addFlash(
            'success',
            'app.invoice_payment.message.deleted'
        );

        return $this->redirectToRoute('invoice_payment_index', array('id' => $invoiceId));
    }

    protected function update(InvoicePayment $entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.invoice_payment.form'),
            '@App/InvoicePayment/include/form.html.twig',
            $entity,
            'app.invoice_payment.message.created',
            'app.invoice_payment.message.updated',
            'invoice_payment_index',
            $this->get('app.hasher')->encodeObject($entity->getInvoice())
        );
    }
}
