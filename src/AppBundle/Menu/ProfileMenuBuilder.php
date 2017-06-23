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

        $menu = $this->factory->createItem('settings');
        //$menu->setChildrenAttribute('class', 'nav nav-tabs');

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
                ),
            )
        );

        /*
        $menu->addChild(
            'app.settings.invoicing',
            array(
                'route' => 'invoicing_settings_view',
            )
        );
        */

        $menu->addChild(
            'app.concession.plural_label',
            array(
                'route' => 'concession_index',
            )
        )->setExtras(
            array(
                'routes' => array(
                    'concession_index',
                    'concession_create',
                    'concession_update',
                    'concession_delete',
                ),
            )
        );

        $menu->addChild(
            'app.calendar_data.label',
            array(
                'route' => 'calendar_data_update',
            )
        )->setExtras(
            array(
                'routes' => array(
                    'calendar_data_update',
                ),
            )
        );

        $menu->addChild(
            'app.treatment_note_template.plural_label',
            array(
                'route' => 'treatment_note_template_index',
            )
        )->setExtras(
            array(
                'routes' => array(
                    'treatment_note_template_index',
                    'treatment_note_template_create',
                    'treatment_note_template_update',
                    'treatment_note_template_delete',
                ),
            )
        );

        return $menu;
    }
}
