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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends EventType
{

    use EventTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addEventSetListener($builder);
        $this->addEventBasicFields($builder, $this->eventUtils);

        $builder->add(
            'patient',
            PatientFieldType::class
        )->add(
            'treatment',
            TreatmentFieldType::class
        );

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
