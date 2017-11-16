<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:38
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Concession;
use AppBundle\Entity\InvoicePaymentMethod;
use AppBundle\Utils\FilterUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

/**
 * InvoicePaymentMethod controller.
 */
class InvoicePaymentMethodController extends Controller
{

    /**
     * Lists all invoice-payment-method entities.
     *
     * @Route("/settings/invoice-payment-method/", name="invoice_payment_method_index", options={"expose"=true})
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('AppBundle:InvoicePaymentMethod')->createQueryBuilder('c');

        /** @var Router $router */
        $router = $this->get('router');

        return $this->get('app.datagrid_utils')->handleDatagrid(
            null,
            $request,
            $qb,
            null,
            '@App/InvoicePaymentMethod/include/grid.html.twig',
            $router->generate('invoice_payment_method_index', [], true)
        );
    }

    /**
     * Creates a new invoice-payment-method entity.
     *
     * @Route("/settings/invoice-payment-method/new", name="invoice_payment_method_create")
     * @Method({"GET", "POST"})
     * @Template("@App/InvoicePaymentMethod/update.html.twig")
     */
    public function createAction(Request $request)
    {
        $invoicePaymentMethod = $this->get('app.entity_factory')->createInvoicePaymentMethod();

        return $this->update($invoicePaymentMethod);
    }

    /**
     * Displays a form to edit an existing invoice_payment_method entity.
     *
     * @Route("/settings/invoice-payment-method/{id}/update", name="invoice_payment_method_update")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, InvoicePaymentMethod $invoicePaymentMethod)
    {
        return $this->update($invoicePaymentMethod);
    }

    /**
     * Deletes a concession entity.
     *
     * @Route("/settings/invoice-payment-method/{id}/delete", name="invoice_payment_method_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, InvoicePaymentMethod $invoicePaymentMethod)
    {
        $em = $this->getDoctrine()->getManager();

        $methodsCount = 0;
        if ($methods = $this->getDoctrine()->getManager()->getRepository('AppBundle:InvoicePaymentMethod')->findAll()) {
            $methodsCount = count($methods);
        }

        if ($methodsCount <= 1) {
            $this->addFlash(
                'danger',
                'app.invoice_payment_method.message.cant_delete'
            );

            return $this->redirectToRoute('practicioner_settings_index');
        }

        try {
            $em->remove($invoicePaymentMethod);
            $em->flush();

            $this->addFlash(
                'success',
                'app.invoice_payment_method.message.deleted'
            );
        } catch (\Exception $exception) {
            $this->addFlash(
                'danger',
                'app.message.undefined_error'
            );
        }

        return $this->redirectToRoute('practicioner_settings_index');
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.invoice_payment_method.form'),
            null,
            $entity,
            'app.invoice_payment_method.message.created',
            'app.invoice_payment_method.message.updated',
            'practicioner_settings_index'
        );
    }
}
