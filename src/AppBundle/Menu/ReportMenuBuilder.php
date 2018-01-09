<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 22:53
 */

namespace AppBundle\Menu;

use AppBundle\Entity\Patient;
use AppBundle\Utils\Hasher;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ReportMenuBuilder
{
    /** @var FactoryInterface */
    protected $factory;

    /** @var RequestStack */
    protected $requestStack;

    /** @var Hasher */
    protected $hasher;

    public function __construct(FactoryInterface $factory, RequestStack $requestStack, Hasher $hasher)
    {
        $this->factory = $factory;
        $this->requestStack = $requestStack;
        $this->hasher = $hasher;
    }

    public function createMenu(array $options)
    {

        $menu = $this->factory->createItem('report');
        $menu->setChildrenAttribute('class', 'menu-sub reports');

        $menu->addChild(
            'app.report.appointments.label',
            array(
                'route' => 'report_appointments',
            )
        );

        $menu->addChild(
            'app.report.patients.label',
            array(
                'route' => 'report_patients',
            )
        );

        $menu->addChild(
            'app.report.invoices.label',
            array(
                'route' => 'report_invoices',
            )
        );

        $menu->addChild(
            'app.report.revenue.label',
            array(
                'route' => 'report_revenue',
            )
        );

        $menu->addChild(
            'app.report.products.label',
            array(
                'route' => 'report_products',
            )
        );

        $menu->addChild(
            'app.report.products_purchased.label',
            array(
                'route' => 'report_products_purchased',
            )
        );

        $menu->addChild(
            'app.report.patient_retention.label',
            array(
                'route' => 'report_patient_retention',
            )
        );

        return $menu;
    }
}
