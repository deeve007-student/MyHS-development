<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 06.07.2017
 * Time: 19:56
 */

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Controller\ProfileController as BaseController;

class ProfileController extends BaseController
{

    /**
     * Displays a form to edit an existing appointment entity.
     *
     * @Route("/settings/general", name="settings_general", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAjaxAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->container->get('fos_user.profile.form');
        $formHandler = $this->container->get('fos_user.profile.form.handler');

        $process = $formHandler->process($user);

        $ajaxResult['error'] = true;
        if ($process) {
            $ajaxResult['message'] = 'profile.flash.updated';
            $ajaxResult['error'] = false;
        }

        $ajaxResult['form'] = $this->container->get('twig')->render(
            'FOSUserBundle:Profile:edit.html.twig',
            array(
                'form' => $form->createView(),
            )
        );

        return new JsonResponse(json_encode($ajaxResult));
    }

}
