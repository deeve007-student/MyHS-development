<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 03.03.2017
 * Time: 18:57
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;

class ProfileType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'title',
            ChoiceType::class,
            array(
                'choices' => array(
                    'Dr',
                    'Ms',
                ),
                'constraints' => array(
                    new IsTrue(),
                ),
            )
        )->add(
            'firstName',
            TextType::class,
            array(
                'required' => true,
            )
        )->add(
            'lastName',
            TextType::class,
            array(
                'required' => true,
            )
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