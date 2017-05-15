<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.05.2017
 * Time: 13:39
 */

namespace AppBundle\Menu;

class InvoiceMobileMenuBuilder extends InvoiceMenuBuilder
{
    protected static $mobile = true;

    public function createMenu(array $options)
    {
        $menu = parent::createMenu($options);
        $menu->setName('invoice_mobile');
        $menu->setChildrenAttribute('class', 'dropdown-menu');

        return $menu;
    }
}
