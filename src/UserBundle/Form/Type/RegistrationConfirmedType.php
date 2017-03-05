<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 23:56
 */

namespace UserBundle\Form\Type;

use AppBundle\Form\Type\CountryFieldType;
use AppBundle\Form\Type\SubscriptionFieldType;
use AppBundle\Form\Type\TimezoneFieldType;
use AppBundle\Form\Type\TitleFieldType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationConfirmedType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'title',
            TitleFieldType::class
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
            'country',
            CountryFieldType::class
        )->add(
            'timezone',
            TimezoneFieldType::class
        )->add(
            'agree',
            CheckboxType::class,
            array(
                'mapped' => false,
                'label' => 'myhs.agree_with_terms',
                'constraints' => array(
                    new IsTrue(),
                ),
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'UserBundle\Entity\User',
                'validation_groups' => array('Start'),
            )
        );
    }

    public function getName()
    {
        return 'myhs_user_registration_confirmed';
    }

}