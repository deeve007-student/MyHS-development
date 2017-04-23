<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 05.03.2017
 * Time: 0:25
 */

namespace UserBundle\Controller;

use AppBundle\Entity\Patient;
use AppBundle\Form\Type\PatientType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Start controller.
 *
 * @Route("start")
 */
class StartController extends Controller
{

    /**
     * Lists all patient entities.
     *
     * @Route("/", name="start")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function startAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        return $this->update($user);
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.registration_confirmed.form'),
            null,
            $entity,
            null,
            null,
            'dashboard_index'
        );
    }
}
