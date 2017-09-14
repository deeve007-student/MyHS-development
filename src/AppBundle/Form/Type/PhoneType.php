<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 15:41
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

class PhoneType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'phoneType',
            ChoiceType::class,
            array(
                //'label' => 'app.phone.type',
                'label' => false,
                'required' => true,
                'placeholder' => 'app.phone.type_choose',
                'choices' => array(
                    'Mobile' => 'Mobile',
                    'Home' => 'Home',
                    'Work' => 'Work',
                    'Other' => 'Other',
                ),
            )
        )->add(
            'phoneNumber',
            TextType::class,
            array(
                //'label' => 'app.phone.label',
                'label' => false,
                'required' => true,
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'error_bubbling' => false,
                'data_class' => 'AppBundle\Entity\Phone',
            )
        );
    }

    public function getName()
    {
        return 'app_phone';
    }

}
