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

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;
use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{
    public function loginAction()
    {
        return $this->container->get('app.security_controller_utils')->loginRegisterAction();
    }

    public function baseLoginAction()
    {
        $loginAttempts = 5;
        $disableLoginMinutes = 60;

        $request = $this->container->get('request');
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $session = $request->getSession();

        if ($session->get('failedCounter', 0) >= $loginAttempts && $session->has('failedDateTime')) {
            $now = new \DateTime();
            $diffInSeconds = $now->getTimestamp() - $session->get('failedDateTime')->getTimestamp();
            if ($diffInSeconds > (60 * $disableLoginMinutes)) {
                $session->set('failedDateTime', null);
                $session->set('failedCounter', 0);
            }
        }

        /* @var $session \Symfony\Component\HttpFoundation\Session\Session */
        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
            $session->set('failedCounter', $session->get('failedCounter', 0) + 1);

            if ($session->get('failedCounter', 0) >= $loginAttempts) {
                $session->set('failedDateTime', new \DateTime());
            }
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->has('form.csrf_provider') ? $this->container->get(
            'form.csrf_provider'
        )->generateCsrfToken('authenticate') : null;

        return array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
        );
    }
}
