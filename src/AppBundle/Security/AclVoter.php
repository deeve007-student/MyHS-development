<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 20.03.2017
 * Time: 9:19
 */

namespace AppBundle\Security;

use AppBundle\Utils\AclUtils;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use UserBundle\Entity\User;

class AclVoter extends Voter
{

    /** @var  AclUtils */
    protected $aclUtils;

    public function __construct(AclUtils $aclUtils)
    {
        $this->aclUtils = $aclUtils;
    }

    public function supports($attribute, $subject)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        return is_object($subject) && $this->aclUtils->supportsAcl($subject) && in_array(
                $attribute,
                array(
                    AclUtils::ACTION_VIEW,
                    AclUtils::ACTION_EDIT,
                    AclUtils::ACTION_DELETE,
                )
            );
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        return $this->aclUtils->isOwner($user, $subject);
    }
}
