<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 20.03.2017
 * Time: 13:30
 */

namespace AppBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class SubdomainListener
{

    /** @var  string */
    protected $baseHost;

    /** @var  ContainerInterface */
    protected $container;

    public function __construct(ContainerInterface $container, $baseHost)
    {
        $this->container = $container;
        $this->baseHost = $baseHost;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        /** @var AuthorizationChecker $authorizationChecker */
        $authorizationChecker = $this->container->get('security.authorization_checker');

        /** @var TokenStorage $tokenStorage */
        $tokenStorage = $this->container->get('security.token_storage');

        if ($tokenStorage->getToken() && $authorizationChecker->isGranted('ROLE_USER')) {
            $subdomain = str_replace('.' . $this->baseHost, '', $request->getHttpHost());
            $subdomain = $subdomain == $this->baseHost ? null : $subdomain;
            $userSlug = $tokenStorage->getToken()->getUser()->getSlug();

            if ((!$subdomain || $subdomain !== $userSlug) && $this->container->getParameter('kernel.environment') == "prod") {
                $url = str_replace($request->getHttpHost(), $userSlug . '.' . $this->baseHost, $request->getUri());
                $event->setResponse(new RedirectResponse($url));
            }
        }
    }

}
