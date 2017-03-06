<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 9:37
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TitleFieldType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'required' => true,
                'label' => 'app.title.label',
                'placeholder' => 'app.title.choose',
                'choices' => array(
                    'Dr' => 'Dr',
                    'Master' => 'Master',
                    'Professor' => 'Professor',
                    'Mr' => 'Mr',
                    'Sir' => 'Sir',
                    'Ms' => 'Ms',
                    'Mrs' => 'Mrs',
                    'Miss' => 'Miss',
                    'Madam' => 'Madam',
                ),
            )
        );
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getName()
    {
        return 'app_title_selector';
    }

}
