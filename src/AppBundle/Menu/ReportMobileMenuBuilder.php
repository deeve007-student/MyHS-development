<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 20.04.2017
 * Time: 14:35
 */

namespace AppBundle\Menu;

use AppBundle\Entity\Patient;
use AppBundle\Utils\Hasher;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ReportMobileMenuBuilder extends ReportMenuBuilder
{
    public function createMenu(array $options)
    {
        $menu = parent::createMenu($options);
        $menu->setName('report_mobile');
        $menu->setChildrenAttribute('class', 'dropdown-menu');

        return $menu;
    }
}

