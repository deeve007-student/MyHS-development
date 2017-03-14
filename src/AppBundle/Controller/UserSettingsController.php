<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 14.03.2017
 * Time: 9:57
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * UserSettings controller.
 *
 * @Route("settings")
 */
class UserSettingsController extends Controller
{

    /**
     * Displays invoicing settings.
     *
     * @Route("/invoicing", name="invoicing_settings_view")
     * @Method("GET")
     * @Template()
     */
    public function viewInvoicingAction()
    {
        return array();
    }

    /**
     * Displays a form to edit an invoicing settings.
     *
     * @Route("/invoicing/update", name="invoicing_settings_update")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateInvoicingAction()
    {
        //return $this->update($userSettings);
    }

    protected function update($entity)
    {
        /*
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.product.form'),
            $entity,
            'product_view',
            $entity->getId()
        );
        */
    }
}
