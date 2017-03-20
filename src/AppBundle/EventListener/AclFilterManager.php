<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 20.03.2017
 * Time: 12:09
 */


namespace AppBundle\EventListener;

use AppBundle\Utils\AclUtils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class AclFilterManager
{

    /** @var AuthorizationChecker */
    protected $authorizationChecker;

    /** @var EntityManager */
    protected $entityManager;

    /** @var TokenStorage */
    protected $tokenStorage;

    /** @var AclUtils */
    protected $aclUtils;

    public function __construct(
        AuthorizationChecker $authorizationChecker,
        TokenStorage $tokenStorage,
        EntityManager $entityManager,
        AclUtils $aclUtils
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->aclUtils = $aclUtils;
    }


    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->entityManager->getConfiguration()->addFilter('acl_filter', 'AppBundle\Security\AclFilter');
        $filter = $this->entityManager->getFilters()->enable('acl_filter');
        $filter->setDependencies($this->authorizationChecker, $this->tokenStorage, $this->aclUtils);

    }

}
