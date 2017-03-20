<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 20.03.2017
 * Time: 9:33
 */

namespace AppBundle\Utils;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use UserBundle\Entity\User;

class AclUtils
{

    const OWNER_FIELD = 'owner';
    const OWNER_FIELD_COLUMN = 'owner_user_id';

    const ACTION_VIEW = 'VIEW';
    const ACTION_EDIT = 'EDIT';
    const ACTION_DELETE = 'DELETE';

    /*
    protected $authorizationChecker;

    public function __construct(AuthorizationChecker $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function filterAcl($array)
    {
        if (is_array($array) || $array instanceof Collection) {
            foreach ($array as $n => $object) {
                if ($this->supportsAcl($object) && !$this->authorizationChecker->isGranted(
                        self::ACTION_VIEW,
                        $object
                    )
                ) {
                    unset($array[$n]);
                }
            }

            return $array;
        } else {
            throw new \Exception('Expected array or collection');
        }
    }
*/

    public function supportsAcl($object)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        return $accessor->isReadable($object, self::OWNER_FIELD) ? true : false;
    }

    public function isOwner($user, $object)
    {
        if (!$user instanceof User) {
            return false;
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        $owner = $accessor->getValue($object, AclUtils::OWNER_FIELD);

        if ($user === $owner) {
            return true;
        }

        return false;
    }

}
