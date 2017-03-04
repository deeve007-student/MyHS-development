<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 03.03.2017
 * Time: 18:57
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
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
        );
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }

    public function getName()
    {
        return 'myhs_user_profile';
    }

}
