<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 13:10
 */

namespace AppBundle\Form\Type;

use AppBundle\Form\DataTransformer\ReferrerTransformer;
use AppBundle\Utils\Hasher;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientType extends AbstractType
{
    /** @var  Hasher */
    protected $hasher;

    /** @var  EntityManager */
    protected $entityManager;

    public function __construct(Hasher $hasher, EntityManager $entityManager)
    {
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;
    }

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
                'label' => 'app.user.first_name',
                'required' => true,
            )
        )->add(
            'concession',
            EntityType::class,
            array(
                'label' => 'app.concession.label',
                'placeholder' => 'app.concession.choose',
                'required' => false,
                'class' => 'AppBundle\Entity\Concession',
            )
        )->add(
            'referrer',
            TextType::class,
            array(
                'label' => 'app.patient.referrer',
                'required' => true,
                'attr' => array(
                    'class' => 'app-patient-referrer',
                    'autocomplete' => 'off',
                ),
            )
        )->add(
            'lastName',
            TextType::class,
            array(
                'label' => 'app.user.last_name',
                'required' => true,
            )
        )->add(
            'preferredName',
            TextType::class,
            array(
                'label' => 'app.patient.preferred_name',
                'required' => false,
            )
        )->add(
            'dateOfBirth',
            DateType::class,
            array(
                'label' => 'app.patient.date_of_birth',
                'required' => false,
                'years' => range(1900, date("Y")),
            )
        )->add(
            'gender',
            ChoiceType::class,
            array(
                'label' => 'app.patient.gender',
                'required' => true,
                'expanded' => false,
                'placeholder' => 'app.patient.choose_gender',
                'choices' => array(
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'Not applicable' => 'Not applicable',
                ),
            )
        )->add(
            'email',
            TextType::class,
            array(
                'label' => 'app.email',
                'required' => false,
            )
        )->add(
            'city',
            TextType::class,
            array(
                'label' => 'app.patient.city',
                'required' => false,
            )
        )->add(
            'postCode',
            TextType::class,
            array(
                'label' => 'app.patient.post_code',
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
        )->add(
            'addressFirst',
            TextType::class,
            array(
                'label' => 'app.patient.patient_address',
                'required' => false,
            )
        )->add(
            'addressSecond',
            TextType::class,
            array(
                'label' => false,
                'required' => false,
            )
        )->add(
            'occupation',
            TextType::class,
            array(
                'label' => 'app.patient.occupation',
                'required' => false,
            )
        )->add(
            'emergencyContact',
            TextType::class,
            array(
                'label' => 'app.patient.emergency_contact',
                'required' => false,
            )
        )->add(
            'healthFund',
            TextareaType::class,
            array(
                'label' => 'app.patient.health_fund',
                'required' => false,
            )
        )->add(
            'notes',
            TextareaType::class,
            array(
                'label' => 'app.patient.notes',
                'required' => false,
            )
        )->add(
            'phones',
            CollectionType::class,
            array(
                'label' => 'app.phone.plural_label',
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
                'label' => 'app.related_patient.plural_label',
                'required' => false,
                'entry_type' => new RelatedPatientType($this->hasher),
                'delete_empty' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            )
        )->add(
            'mobilePhone',
            TextType::class,
            array(
                'label' => 'app.patient.mobile_phone',
                'required' => true,
            )
        );

        // Todo: move referrer field to separate form type

        $builder->get('referrer')->addModelTransformer(new ReferrerTransformer($this->entityManager));
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
        return 'app_patient';
    }

}
