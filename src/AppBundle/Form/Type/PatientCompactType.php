<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 11.06.2017
 * Time: 13:39
 */

namespace AppBundle\Form\Type;

use AppBundle\Form\DataTransformer\ReferrerTransformer;
use AppBundle\Utils\Hasher;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientCompactType extends AbstractType
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
            'firstName',
            TextType::class,
            array(
                'label' => 'app.user.first_name',
                'required' => true,
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
            'dateOfBirth',
            DateType::class,
            array(
                'label' => 'app.patient.date_of_birth',
                'required' => false,
                'years' => range(1900, date("Y")),
            )
        )->add(
            'email',
            TextType::class,
            array(
                'label' => 'app.email',
                'required' => false,
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
        return 'app_patient_compact';
    }

}
