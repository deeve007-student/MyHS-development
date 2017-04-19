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

class PatientMenuBuilder
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

    protected function getPatientId()
    {
        if ($patientId = $this->requestStack->getCurrentRequest()->get('patient')) {
            if ($patientId instanceof Patient) {
                return $this->hasher->encodeObject($patientId);
            } else {
                return $patientId;
            }
        }
        if ($patientId = $this->requestStack->getCurrentRequest()->get('id')) {
            if ($patientId instanceof Patient) {
                return $this->hasher->encodeObject($patientId);
            } else {
                return $patientId;
            }
        }

        return null;
    }

    public function createMenu(array $options)
    {

        $menu = $this->factory->createItem('sidebar');
        $menu->setChildrenAttribute('class', 'menu-sub');

        $menu->addChild(
            'app.patient.patient_details',
            array(
                'route' => 'patient_view',
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

        $menu->addChild(
            'app.treatment_note.plural_label',
            array(
                'uri' => '#',
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

        $menu->addChild(
            'app.invoice.plural_label',
            array(
                'route' => 'patient_invoice_index',
                'routeParameters' => array(
                    'id' => $this->getPatientId(),
                ),
            )
        )->setExtras(
            array(
                'routes' => array(
                    'patient_invoice_index',
                    'patient_invoice_create',
                    'patient_invoice_view',
                    'patient_invoice_update',
                ),
            )
        );

        $menu->addChild(
            'app.attachment.plural_label',
            array(
                'route' => 'patient_attachment_index',
                'routeParameters' => array(
                    'id' => $this->getPatientId(),
                ),
            )
        )->setExtras(
            array(
                'routes' => array(
                    'patient_attachment_index',
                    'attachment_create_from_patient',
                ),
            )
        );

        return $menu;
    }
}
