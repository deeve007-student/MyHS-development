<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 20.03.2017
 * Time: 10:25
 */

namespace AppBundle\Security;

use AppBundle\Utils\AclUtils;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use UserBundle\Entity\User;

class AclFilter extends SQLFilter
{

    /** @var AuthorizationChecker */
    protected $authorizationChecker;

    /** @var TokenStorage */
    protected $tokenStorage;

    /** @var AclUtils */
    protected $aclUtils;

    public function setDependencies(
        AuthorizationChecker $authorizationChecker,
        TokenStorage $tokenStorage,
        AclUtils $aclUtils
    )
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->aclUtils = $aclUtils;
    }

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        $query = '';

        $user = null;
        if ($this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getUser()) {
            if ($this->tokenStorage->getToken()->getUser() instanceof User) {
                $user = $this->tokenStorage->getToken()->getUser();
            }
        }

        if ($user) {

            if ($targetEntity->getReflectionClass()->hasProperty(AclUtils::OWNER_FIELD)) {
                $query = sprintf(
                    '%s.%s = %s',
                    $targetTableAlias,
                    AclUtils::OWNER_FIELD_COLUMN,
                    $this->tokenStorage->getToken()->getUser()->getId()
                );
            }

        }

        return $query;

    }

}
