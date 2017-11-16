<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:38
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * InvoiceSettings controller.
 */
class InvoiceSettingsController extends Controller
{
    /**
     * @Route("/settings/invoice", name="invoice_settings_update", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request)
    {
        return $this->update($this->getUser()->getInvoiceSettings());
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.invoice_settings.form'),
            'AppBundle:InvoiceSettings:update.html.twig',
            $entity,
            '',
            'app.invoice_settings.message.updated',
            'invoice_settings_update'
        );
    }
}
