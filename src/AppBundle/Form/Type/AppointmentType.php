<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 19:25
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'description',
            TextareaType::class,
            array(
                'required' => true,
                'label' => 'app.appointment.description',
            )
        )->add(
            'patient',
            PatientFieldType::class
        )->add(
            'start',
            DateTimeType::class,
            [
                'label'=>'app.appointment.start'
            ]
        )->add(
            'end',
            DateTimeType::class,
            [
                'label'=>'app.appointment.end'
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Appointment',
            )
        );
    }

    public function getName()
    {
        return 'app_appointment';
    }

}
