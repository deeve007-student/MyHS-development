<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 19.03.2017
 * Time: 18:11
 */

namespace AppBundle\Controller;

use AppBundle\Utils\AclUtils;
use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\VarDumper\VarDumper;

class Controller extends BaseController
{

    protected function dumpDie($data)
    {
        $dumper = new VarDumper();
        $dumper->dump($data);
        die();
    }

    protected function dump($data)
    {
        $dumper = new VarDumper();
        $dumper->dump($data);
    }

    /**
     * @return \AppBundle\Utils\EventUtils
     */
    protected function getEventUtils() {
        return $this->get('app.event_utils');
    }

    protected function getEventAdditionalData(Request $request, $default = array())
    {
        $additionalData = $default;
        if ($title = $request->get('title')) {
            $additionalData['title'] = $title;
        }
        $additionalData = $default;
        if ($title = $request->get('submit')) {
            $additionalData['submit'] = $title;
        }
        return $additionalData;
    }

    /*
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
    */

}
