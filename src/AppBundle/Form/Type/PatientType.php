<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 13:10
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'title',
            TitleFieldType::class,
            array(
                'required' => false,
            )
        )->add(
            'firstName',
            TextType::class,
            array(
                'label' => 'myhs.user.first_name',
                'required' => true,
            )
        )->add(
            'lastName',
            TextType::class,
            array(
                'label' => 'myhs.user.last_name',
                'required' => true,
            )
        )->add(
            'preferredName',
            TextType::class,
            array(
                'label' => 'myhs.patient.preferred_name',
                'required' => false,
            )
        )->add(
            'dateOfBirth',
            DateType::class,
            array(
                'label' => 'myhs.patient.date_of_birth',
                'required' => false,
                'years' => range(1900, date("Y")),
            )
        )->add(
            'gender',
            ChoiceType::class,
            array(
                'label' => 'myhs.patient.gender',
                'required' => true,
                'expanded' => true,
                'choices' => array(
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'Not applicable' => 'Not applicable',

                ),
            )
        )->add(
            'email',
            EmailType::class,
            array(
                'label' => 'myhs.email',
                'required' => false,
            )
        )->add(
            'city',
            TextType::class,
            array(
                'label' => 'myhs.patient.city',
                'required' => false,
            )
        )->add(
            'state',
            StateFieldType::class,
            array(
                'required' => false,
            )
        )->add(
            'autoRemindSMS',
            CheckboxType::class,
            array(
                'label' => 'myhs.patient.auto_remind_sms',
                'required' => false,
            )
        )->add(
            'autoRemindEmail',
            CheckboxType::class,
            array(
                'label' => 'myhs.patient.auto_remind_email',
                'required' => false,
            )
        )->add(
            'bookingConfirmationEmail',
            CheckboxType::class,
            array(
                'label' => 'myhs.patient.booking_confirmation_email',
                'required' => false,
            )
        )->add(
            'occupation',
            TextType::class,
            array(
                'label' => 'myhs.patient.occupation',
                'required' => false,
            )
        )->add(
            'emergencyContact',
            TextType::class,
            array(
                'label' => 'myhs.patient.emergency_contact',
                'required' => false,
            )
        )->add(
            'healthFund',
            TextareaType::class,
            array(
                'label' => 'myhs.patient.health_fund',
                'required' => false,
            )
        )->add(
            'referrer',
            TextType::class,
            array(
                'label' => 'myhs.patient.referrer',
                'required' => false,
            )
        )->add(
            'notes',
            TextareaType::class,
            array(
                'label' => 'myhs.patient.notes',
                'required' => false,
            )
        )->add(
            'phones',
            CollectionType::class,
            array(
                'label' => 'myhs.phone.plural_label',
                'required' => false,
                'entry_type' => new PhoneType(),
                'delete_empty' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            )
        )->add(
            'relatedPatients',
            CollectionType::class,
            array(
                'label' => 'myhs.related_patient.plural_label',
                'required' => false,
                'entry_type' => new RelatedPatientType(),
                'delete_empty' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Patient',
            )
        );
    }

    public function getName()
    {
        return 'myhs_patient';
    }

}
