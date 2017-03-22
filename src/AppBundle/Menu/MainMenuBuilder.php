<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 22.03.2017
 * Time: 9:12
 */

namespace AppBundle\Menu;

use AppBundle\Entity\Patient;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class MainMenuBuilder
{
    /** @var FactoryInterface */
    protected $factory;

    /** @var RequestStack */
    protected $requestStack;

    /** @var  AuthorizationChecker */
    protected $authorizationChecker;

    public function __construct(
        FactoryInterface $factory,
        RequestStack $requestStack,
        AuthorizationChecker $authorizationChecker
    ) {
        $this->factory = $factory;
        $this->requestStack = $requestStack;
        $this->authorizationChecker = $authorizationChecker;
    }

}
