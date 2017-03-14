<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 22:53
 */

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PatientMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    public function __construct(FactoryInterface $factory, RequestStack $requestStack)
    {
        $this->factory = $factory;
        $this->requestStack = $requestStack;
    }

    public function createMenu(array $options)
    {

        $menu = $this->factory->createItem('sidebar');
        $menu->setChildrenAttribute('class', 'nav nav-tabs');

        $menu->addChild(
            'app.patient.label',
            array(
                'route' => 'patient_view',
                'routeParameters' => array(
                    'id' => $this->requestStack->getCurrentRequest()->get('id'),
                ),
            )
        )->setExtras(
            array(
                'routes' => array(
                    'patient_view',
                    'patient_update',
                ),
            )
        );

        /*
        $menu->addChild(
            'app.treatment_note.plural_label',
            array(
                'uri' => '#',
            )
        )->setExtras(
            array(
                'routes' => array(),
            )
        );

        $menu->addChild(
            'app.attachment.plural_label',
            array(
                'uri' => '#',
            )
        )->setExtras(
            array(
                'routes' => array(),
            )
        );

        $menu->addChild(
            'app.appointment.plural_label',
            array(
                'uri' => '#',
            )
        )->setExtras(
            array(
                'routes' => array(),
            )
        );

        $menu->addChild(
            'app.message.plural_label',
            array(
                'uri' => '#',
            )
        )->setExtras(
            array(
                'routes' => array(),
            )
        );

        $menu->addChild(
            'app.reminder.plural_label',
            array(
                'uri' => '#',
            )
        )->setExtras(
            array(
                'routes' => array(),
            )
        );
        */

        $menu->addChild(
            'app.invoice.plural_label',
            array(
                'route' => 'patient_invoice_index',
                'routeParameters' => array(
                    'id' => $this->requestStack->getCurrentRequest()->get('id'),
                ),
            )
        )->setExtras(
            array(
                'routes' => array(
                    'patient_invoice_index',
                ),
            )
        );

        return $menu;
    }
}
