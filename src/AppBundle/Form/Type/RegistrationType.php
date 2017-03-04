<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 03.03.2017
 * Time: 19:49
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'agree',
            CheckboxType::class,
            array(
                'mapped' => false,
                'label' => 'myhs.agree_with_terms',
                'constraints' => array(
                    new IsTrue(),
                ),
            )
        )->add(
            'country',
            CountryFieldType::class
        )->add(
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
            'timezone',
            TimezoneFieldType::class
        );
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'myhs_user_registration';
    }

}
