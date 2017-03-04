<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 10:00
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimezoneFieldType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'required' => true,
                'label' => 'myhs.timezone.label',
                'placeholder' => 'myhs.timezone.choose',
                'choices' => array(
                    '+8:00' => '+8:00 Western Standard Time',
                    '+8:45' => '+8:45 Central Western Standard Time',
                    '+9:30' => '+9:30 Central Standard Time',
                    '+10:00' => '+10:00 Eastern Standard Time',
                    '+10:30' => '+10:30 Lord Howe Standard Time',
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
        return 'myhs_timezone_selector';
    }

}
