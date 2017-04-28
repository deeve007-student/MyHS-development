<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 28.04.2017
 * Time: 10:15
 */

namespace UserBundle\Utils;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Controller\RegistrationController;
use UserBundle\Controller\SecurityController;

class SecurityControllerUtils
{
    /** @var  SecurityController */
    var $loginController;

    /** @var  RegistrationController */
    var $registrationController;

    /** @var  ContainerInterface */
    var $container;

    public function __construct(
        SecurityController $loginController,
        RegistrationController $registrationController,
        ContainerInterface $container
    )
    {
        $this->loginController = $loginController;
        $this->registrationController = $registrationController;
        $this->container = $container;
    }

    public function loginRegisterAction()
    {
        $data = $this->getLoginRegisterData();

        if ($data instanceof Response) {
            return $data;
        }

        if ($data instanceof Response) {
            return $data;
        }

        $template = sprintf('FOSUserBundle::auth.html.%s', $this->container->getParameter('fos_user.template.engine'));
        return $this->container->get('templating')->renderResponse($template, $data);
    }

    public function getLoginRegisterData()
    {
        $loginData = $this->loginController->baseLoginAction();
        $registerData = $this->registrationController->baseRegisterAction();

        if ($loginData instanceof Response) {
            return $loginData;
        }

        if ($registerData instanceof Response) {
            return $registerData;
        }

        return array_merge(array('tab' => $this->getCurrentTab()), $loginData, $registerData);
    }

    protected function getCurrentTab()
    {
        $controllerFQCN = explode('::', $this->container->get('request_stack')->getCurrentRequest()->get('_controller'))[0];
        $controllerArr = explode('\\', $controllerFQCN);
        $controller = $controllerArr[count($controllerArr) - 1];

        if ($controller == 'SecurityController') {
            return 'login';
        }
        if ($controller == 'RegistrationController') {
            return 'register';
        }

        return null;
    }
}
