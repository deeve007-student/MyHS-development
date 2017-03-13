<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:14
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * Displays a form to edit an existing invoice entity.
     *
     * @Route("/update/{id}", name="invoice_update")
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
     * @Route("/delete/{id}", name="invoice_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Invoice $invoice)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($invoice);
        $em->flush();

        return $this->redirectToRoute('invoice_index');
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.invoice.form'),
            $entity,
            'invoice_view',
            $entity->getId()
        );
    }
}
