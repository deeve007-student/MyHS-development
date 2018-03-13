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
 * Refund controller.
 *
 * Route("refund")
 */
class RefundController extends Controller
{

    /**
     * @Route("/refund/{invoice}", name="refund_invoice_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/Refund/invoice.html.twig")
     */
    public function createFromPatientAction(Invoice $invoice)
    {
        $refund = new Refund();
        $refund->setInvoice($invoice);

        $result = $this->update($refund);
        return $result;
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.invoice_refund.form'),
            null,
            $entity,
            'app.refund.message.created',
            'app.refund.message.updated',
            null,
            $this->get('app.hasher')->encodeObject($entity)
        );
    }

}
