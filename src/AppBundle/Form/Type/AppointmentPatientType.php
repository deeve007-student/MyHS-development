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
use Symfony\Component\Form\FormBuilderInterface;
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
        );
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\AppointmentPatient',
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
