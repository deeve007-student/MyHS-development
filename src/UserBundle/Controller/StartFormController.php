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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Start controller.
 *
 * @Route("start")
 */
class StartFormController extends Controller
{

    /**
     * Show user details form at first login
     *
     * @Route("/", name="start")
     * @Method({"GET", "POST"})
     * @Template("@User/Start/start.html.twig")
     */
    public function startAction()
    {
        /** @var TokenStorage $tokenStorage */
        $tokenStorage = $this->get('security.token_storage');
        $user = $tokenStorage->getToken()->getUser();

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
