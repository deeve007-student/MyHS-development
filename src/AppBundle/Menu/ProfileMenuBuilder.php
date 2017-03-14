<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 22:53
 */

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;

class ProfileMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMenu(array $options)
    {

        $menu = $this->factory->createItem('sidebar');
        $menu->setChildrenAttribute('class', 'nav nav-tabs');

        $menu->addChild(
            'app.general',
            array(
                'route' => 'fos_user_profile_show',
            )
        )->setExtras(
            array(
                'routes' => array(
                    'fos_user_profile_show',
                    'fos_user_profile_edit',
                )
            )
        );

        $menu->addChild(
            'app.settings.invoicing',
            array(
                'route' => 'invoicing_settings_view',
            )
        );

        /*
        $menu->addChild(
            'change_password.submit',
            array(
                'route' => 'fos_user_change_password',
            )
        )->setExtra('translation_domain', 'FOSUserBundle');
        */

        return $menu;
    }
}
