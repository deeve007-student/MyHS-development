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
class CommunicationsSettingsController extends Controller
{
    /**
     * @Route("/settings/communications", name="communications_settings_update", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request)
    {
        return $this->update($this->getUser()->getCommunicationsSettings());
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.communications_settings.form'),
            'AppBundle:CommunicationsSettings:update.html.twig',
            $entity,
            '',
            'app.communications_settings.message.updated',
            'communications_settings_update'
        );
    }
}
