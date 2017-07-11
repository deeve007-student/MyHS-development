<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends BaseController
{

    /**
     * Displays a form to change password.
     *
     * @Route("/settings/security", name="settings_security", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function changePasswordAjaxAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->container->get('fos_user.change_password.form');
        $formHandler = $this->container->get('fos_user.change_password.form.handler');

        $process = $formHandler->process($user);

        $ajaxResult['error'] = true;
        if ($process) {
            $ajaxResult['message'] = 'change_password.flash.success';
            $ajaxResult['error'] = false;
        }

        $ajaxResult['form'] = $this->container->get('twig')->render(
            '@FOSUser/ChangePassword/changePassword.html.twig',
            array(
                'form' => $form->createView(),
            )
        );

        return new JsonResponse(json_encode($ajaxResult));
    }
}
