<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 21.08.2017
 * Time: 20:06
 */

namespace AppBundle\EventListener;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class TimezoneListener
{

    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->container->get('security.token_storage')->getToken() && $this->container->get('security.token_storage')->getToken()->getUser()) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            if (is_object($user)) {
                if ($user->getTimezone()) {
                    date_default_timezone_set($user->getTimezone());
                }
            }
        }
    }
}
