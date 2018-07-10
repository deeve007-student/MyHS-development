<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 02.07.2018
 * Time: 16:17
 */

namespace AppBundle\Form\Type;

use AppBundle\Form\Traits\AddFieldOptionsTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AppointmentPatientType
 */
class AppointmentPatientType extends AbstractType
{

    use AddFieldOptionsTrait;

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'patient',
            PatientFieldType::class
        )->add(
            'invoice',
            EntityType::class,
            [
                'required' => false,
                'class' => 'AppBundle\Entity\Invoice',
            ]
        )->add(
            'newPatient',
            PatientCompactType::class,
            array(
                'required' => false,
                'mapped' => false,
            )
        )->add(
            'selectOrCreatePatient',
            TextType::class,
            array(
                'mapped' => false,
                'data' => 'select',
            )
        );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if (empty($data['patient']) && $data['selectOrCreatePatient'] == 'new') {
                $form->add(
                    'newPatient',
                    PatientCompactType::class,
                    [
                        'required' => true,
                        'mapped' => true,
                        'property_path' => 'patient',
                    ]
                );
            }
        });
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\AppointmentPatient',
                'validation_groups' => array(
                    'Appointment'
                ),
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_appointment_patient';
    }

}
