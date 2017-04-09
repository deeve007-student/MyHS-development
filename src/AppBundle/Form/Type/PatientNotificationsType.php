<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 09.04.2017
 * Time: 15:53
 */

namespace AppBundle\Form\Type;

use AppBundle\Utils\Hasher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientNotificationsType extends AbstractType
{
    /** @var  Hasher */
    protected $hasher;

    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'autoRemindSMS',
            CheckboxType::class,
            array(
                'label' => 'app.patient.auto_remind_sms',
                'required' => false,
            )
        )->add(
            'autoRemindEmail',
            CheckboxType::class,
            array(
                'label' => 'app.patient.auto_remind_email',
                'required' => false,
            )
        )->add(
            'bookingConfirmationEmail',
            CheckboxType::class,
            array(
                'label' => 'app.patient.booking_confirmation_email',
                'required' => false,
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Patient',
                'csrf_protection' => false,
            )
        );
    }

    public function getName()
    {
        return 'app_patient_notifications';
    }

}
