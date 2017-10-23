<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 22.03.2017
 * Time: 9:12
 */

namespace AppBundle\Menu;

use AppBundle\Entity\Patient;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class MainMenuBuilder
{
    /** @var FactoryInterface */
    protected $factory;

    /** @var RequestStack */
    protected $requestStack;

    /** @var  AuthorizationChecker */
    protected $authorizationChecker;

    public function __construct(
        FactoryInterface $factory,
        RequestStack $requestStack,
        AuthorizationChecker $authorizationChecker
    )
    {
        $this->factory = $factory;
        $this->requestStack = $requestStack;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createMenu(array $options)
    {

        $menu = $this->factory->createItem('main');
        $menu->setChildrenAttribute('class', 'nav navmenu-nav');

        $menu->addChild(
            'app.dashboard.label',
            array(
                'route' => 'dashboard_index',
            )
        )->setExtras(
            array(
                'routes' => array(
                    'dashboard_index',
                ),
            )
        )->setAttribute('class', 'menu-dashboard');

        $menu->addChild(
            'app.calendar.label',
            array(
                'route' => 'calendar_index',
            )
        )->setExtras(
            array(
                'routes' => array(
                    'calendar_index',
                ),
            )
        )->setAttribute('class', 'menu-calendar');

        $menu->addChild(
            'app.patient.plural_label',
            array(
                'route' => 'patient_index',
            )
        )->setExtras(
            array(
                'routes' => array(
                    'patient_index',
                    'patient_alert_create',
                    'patient_alert_view',
                    'patient_alert_update',
                    'patient_alert_delete',
                    'treatment_note_index',
                    'treatment_note_create',
                    'treatment_note_update',
                    'treatment_note_delete',
                    'patient_index',
                    'patient_attachment_index',
                    'patient_create',
                    'patient_view',
                    'patient_address_view',
                    'patient_names',
                    'patient_update',
                    'patient_delete',
                    'patient_invoice_index',
                    'patient_invoice_create',
                    'patient_invoice_view',
                    'patient_invoice_update',
                    'patient_invoice_delete',
                    'patient_invoice_status_update',
                    'patient_invoice_duplicate',
                    'attachment_download',
                    'attachment_open',
                    'attachment_create_from_patient',
                    'attachment_delete',
                ),
            )
        )->setAttribute('class', 'menu-patients');

        $menu->addChild(
            'app.invoice.plural_label',
            array(
                'route' => 'invoice_index',
            )
        )->setExtras(
            array(
                'routes' => array(
                    'invoice_index',
                    'invoice_create',
                    'invoice_view',
                    'invoice_status_update',
                    'invoice_duplicate',
                    'invoice_update',
                    'invoice_delete',
                    'invoice_pdf_send',
                    'invoice_pdf',
                ),
            )
        )->setAttribute('class', 'menu-invoices');

        $menu->addChild(
            'app.treatment.plural_label',
            array(
                'route' => 'treatment_index',
            )
        )->setExtras(
            array(
                'routes' => array(
                    'treatment_index',
                    'treatment_update',
                    'treatment_create',
                    'treatment_delete',
                    'treatment_price_view',
                ),
            )
        )->setAttribute('class', 'menu-treatments');

        $menu->addChild(
            'app.product.plural_label',
            array(
                'route' => 'product_index',
            )
        )->setExtras(
            array(
                'routes' => array(
                    'product_index',
                    'product_create',
                    'product_update',
                    'product_delete',
                    'product_price_view',
                ),
            )
        )->setAttribute('class', 'menu-products');

        $menu->addChild(
            'app.message_log.label',
            array(
                'route' => 'message_log_index',
            )
        )->setExtras(
            array(
                'routes' => array(
                    'message_log_index',
                ),
            )
        )->setAttribute('class', 'menu-messages');

        $menu->addChild(
            'Reports',
            array(
                'route' => 'report_index',
            )
        )->setAttribute('class', 'menu-reports');

        /*
        $menu->addChild(
            'app.dashboard.label',
            array(
                'route' => 'root',
                'routeParameters' => array(
                    'id' => $this->getPatientId(),
                ),
            )
        )->setExtras(
            array(
                'routes' => array(
                    'patient_view',
                    'patient_update',
                    'patient_alert_update',
                    'patient_alert_create',
                ),
            )
        );
        */

        return $menu;
    }
}
