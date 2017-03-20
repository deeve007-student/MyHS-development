<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 19.03.2017
 * Time: 18:11
 */

namespace AppBundle\Controller;

use AppBundle\Utils\AclUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class Controller extends BaseController
{

    const ITEMS_PER_PAGE = 15;

    protected function filterAcl($data)
    {
        return $this->get('app.acl_utils')->filterAcl($data);
    }

    protected function canView($entity)
    {
        if (!$this->get('security.authorization_checker')->isGranted(AclUtils::ACTION_VIEW, $entity)) {
            throw new AccessDeniedException();
        }
    }

    protected function canEdit($entity)
    {
        if (!$this->get('security.authorization_checker')->isGranted(AclUtils::ACTION_EDIT, $entity)) {
            throw new AccessDeniedException();
        }
    }

    protected function canDelete($entity)
    {
        if (!$this->get('security.authorization_checker')->isGranted(AclUtils::ACTION_DELETE, $entity)) {
            throw new AccessDeniedException();
        }
    }

}