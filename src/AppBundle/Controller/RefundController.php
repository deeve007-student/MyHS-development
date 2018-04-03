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
use AppBundle\Entity\TreatmentPackCredit;
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
 * Refund controller.
 *
 * Route("refund")
 */
class RefundController extends Controller
{

    /**
     * @Route("/refund/invoice/{invoice}", name="refund_invoice_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Refund/invoice.html.twig")
     */
    public function createFromPatientAction(Request $request, Invoice $invoice)
    {
        $refund = new Refund();
        $refund->setInvoice($invoice);
        $result = $this->update($refund);

        $form = $this->get('app.invoice_refund.form');
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->get('items')->getData() as $rawItem) {
                if ($rawItem['amount'] > 0) {
                    $refundItem = new InvoiceRefund();
                    $refundItem->setAmount($rawItem['amount'])
                        ->setPaymentMethod($rawItem['item']);
                    $refund->addItem($refundItem);
                }
            }
            $this->getDoctrine()->getManager()->flush();
        }

        return $result;
    }

    /**
     * @Route("/refund/pack/{pack}", name="refund_pack_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Refund/pack.html.twig")
     */
    public function createPackRefundAction(Request $request, TreatmentPackCredit $pack)
    {
        $refund = new Refund();
        $refund->setInvoice($pack->getInvoiceProduct()->getInvoice());
        $result = $this->updatePack($pack);

        /*
        $form = $this->get('app.pack_refund.form');
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->get('items')->getData() as $rawItem) {
                if ($rawItem['amount'] > 0) {
                    $refundItem = new InvoiceRefund();
                    $refundItem->setAmount($rawItem['amount'])
                        ->setPaymentMethod($rawItem['item']);
                    $refund->addItem($refundItem);
                }
            }
            $this->getDoctrine()->getManager()->flush();
        }
        */

        return $result;
    }

    /**
     * Deletes a refund entity.
     *
     * @Route("/refund/{id}/delete", name="refund_delete", options={"expose"=true})
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, InvoiceRefund $refund)
    {
        $em = $this->getDoctrine()->getManager();
        $invoiceId = $this->get('app.hasher')->encodeObject($refund->getRefund()->getInvoice());

        $em->remove($refund);
        $em->flush();

        $this->addFlash(
            'success',
            'app.refund.message.deleted'
        );

        return $this->redirectToRoute('invoice_payment_index', array('id' => $invoiceId));
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.invoice_refund.form'),
            'AppBundle:Refund/include:form.html.twig',
            $entity,
            'app.refund.message.created',
            'app.refund.message.updated',
            null,
            $this->get('app.hasher')->encodeObject($entity)
        );
    }

    protected function updatePack($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.pack_refund.form'),
            'AppBundle:Refund/include:formPack.html.twig',
            $entity,
            'app.refund.message.created',
            'app.refund.message.updated',
            null,
            $this->get('app.hasher')->encodeObject($entity)
        );
    }

}
