<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 06.06.2017
 * Time: 12:35
 */

namespace AppBundle\Form\Traits;

use AppBundle\Entity\Event;
use AppBundle\Form\Type\DateType;
use AppBundle\Form\Type\TimeType;
use AppBundle\Utils\EventUtils;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

trait EventTrait
{

    protected function addEventSetListener(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            if ($data instanceof Event) {
                $form->get('start')->setData($data->getStart());
                $form->get('end')->setData($data->getEnd());
                $form->get('date')->setData($data->getStart());
            }
        });
    }

    protected function addEventSubmitListener(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $formData = $event->getForm()->getViewData();

            if (isset($data['date']) && isset($data['start']) && isset($data['end'])) {
                $formData->setStart(new \DateTime($data['date'] . ' ' . $data['start']));
                $formData->setEnd(new \DateTime($data['date'] . ' ' . $data['end']));
                $event->getForm()->setData($formData);
            }
        });
    }

    protected function addEventBasicFields(FormBuilderInterface $builder, EventUtils $eventUtils)
    {
        $builder->add(
            'description',
            TextareaType::class,
            array(
                'required' => true,
                'label' => 'app.appointment.description',
            )
        )->add(
            'date',
            DateType::class,
            [
                'label' => 'app.date',
                'mapped' => false,
            ]
        )->add(
            'start',
            TimeType::class,
            [
                'label' => 'app.appointment.start',
                'mapped' => false,
                'use_interval' => true,
            ]
        )->add(
            'end',
            TimeType::class,
            [
                'label' => 'app.appointment.end',
                'mapped' => false,
                'use_interval' => true,
            ]
        )->add(
            'resource',
            EntityType::class,
            [
                'label' => 'app.event_resource.label',
                'class' => 'AppBundle\Entity\EventResource',
                'placeholder' => 'app.event_resource.choose',
                'choices' => $eventUtils->getResources(),
            ]
        );
    }

}
