<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 19.03.2017
 * Time: 23:12
 */

namespace UserBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class FirstLoginListener
{

    /** @var  AuthorizationChecker */
    protected $authorizationChecker;

    /** @var  TokenStorage */
    protected $tokenStorage;

    /** @var  Router */
    protected $router;

    public function __construct(
        AuthorizationChecker $authorizationChecker,
        TokenStorage $tokenStorage,
        Router $router
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $startUrl = $this->router->generate('start');
        $currentUrl = $event->getRequest()->getRequestUri();

        if ($currentUrl !== $startUrl &&
            $this->tokenStorage->getToken() &&
            $this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') &&
            $this->authorizationChecker->isGranted('ROLE_USER') &&
            $this->tokenStorage->getToken()->getUser()->getFirstLogin()
        ) {
            $event->setResponse(new RedirectResponse($startUrl));
        }
    }

}
