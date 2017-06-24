<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 19:25
 */

namespace AppBundle\Form\Type;

use AppBundle\Form\Traits\EventTrait;
use AppBundle\Utils\Formatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends EventType
{

    use EventTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addEventSetListener($builder);
        $this->addEventBasicFields($builder, $this->eventUtils);

        $builder->add(
            'selectOrCreatePatient',
            TextType::class,
            array(
                'mapped' => false,
                'data' => 'select',
            )
        )->add(
            'patient',
            PatientFieldType::class
        )->add(
            'newPatient',
            PatientCompactType::class,
            array(
                'required' => false,
                'mapped' => false,
                //'property_path' => 'patient',
            )
        )->add(
            'treatment',
            TreatmentFieldType::class
        );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if (empty($data['patient']) && $data['selectOrCreatePatient'] == 'new') {
                $this->isNew = true;
                $form->add(
                    'newPatient',
                    PatientCompactType::class,
                    array(
                        'required' => true,
                        'mapped' => true,
                        'property_path' => 'patient',
                    )
                );
            }
        });

        $this->addEventSubmitListener($builder);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Appointment',
                'validation_groups' => array(
                    'Appointment'
                ),
            )
        );
    }

    public function getName()
    {
        return 'app_appointment';
    }

}
