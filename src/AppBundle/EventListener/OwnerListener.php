<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 20.03.2017
 * Time: 8:40
 */

namespace AppBundle\EventListener;

use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use AppBundle\Utils\AclUtils;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class OwnerListener
{

    use RecomputeChangesTrait;

    /** @var  TokenStorage */
    protected $tokenStorage;

    public function __construct(
        TokenStorage $tokenStorage
    )
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser()
        ) {
            $user = $this->tokenStorage->getToken()->getUser();
            if ((string)$user !== 'anon.') {

                $accessor = PropertyAccess::createPropertyAccessor();
                if ($accessor->isWritable($entity, AclUtils::OWNER_FIELD) && $accessor->isReadable(
                        $entity,
                        AclUtils::OWNER_FIELD
                    ) && !$accessor->getValue($entity, AclUtils::OWNER_FIELD)
                ) {
                    $accessor->setValue($entity, 'owner', $user);
                }

            }
        }

    }

}
