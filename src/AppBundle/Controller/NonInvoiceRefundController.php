<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:14
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoiceRefund;
use AppBundle\Entity\InvoiceTreatment;
use AppBundle\Entity\Message;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Refund;
use AppBundle\Utils\AppNotificator;
use AppBundle\Utils\FilterUtils;
use AppBundle\Utils\Templater;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Non-invoice refund controller.
 *
 * Route("non-invoice-refund")
 */
class NonInvoiceRefundController extends Controller
{

    /**
     * @Route("/non-invoice-refund/create", name="refund_noninvoice_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function createAction(Request $request)
    {
        $refund = new Refund();
        $result = $this->update($refund);

        $form = $this->get('app.non_invoice_refund.form');
        if ($form->isSubmitted() && $form->isValid()) {
            $amount = $form->get('amount')->getData();
            $paymentMethod = $form->get('paymentMethod')->getData();

            $refundItem = new InvoiceRefund();
            $refundItem->setAmount($amount)
                ->setPaymentMethod($paymentMethod);
            $refund->addItem($refundItem);

            $this->getDoctrine()->getManager()->flush();
        }

        return $result;
    }

    /**
     * @Route("/non-invoice-refund/{id}/update", name="refund_noninvoice_update", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function updateAction(Request $request, Refund $refund)
    {
        $result = $this->update($refund);

        $form = $this->get('app.non_invoice_refund.form');
        if ($form->isSubmitted() && $form->isValid()) {
            $amount = $form->get('amount')->getData();
            $paymentMethod = $form->get('paymentMethod')->getData();

            $refundItem = $refund->getItems()->first();
            $refundItem->setAmount($amount)
                ->setPaymentMethod($paymentMethod);

            $this->getDoctrine()->getManager()->flush();
        }

        return $result;
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.non_invoice_refund.form'),
            'AppBundle:NonInvoiceRefund/include:form.html.twig',
            $entity,
            'app.non_invoice_refund.message.created',
            'app.non_invoice_refund.message.updated',
            null,
            $this->get('app.hasher')->encodeObject($entity)
        );
    }

}
