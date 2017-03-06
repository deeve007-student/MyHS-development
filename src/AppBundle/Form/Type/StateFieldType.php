<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 13:21
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StateFieldType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'class' => 'AppBundle\Entity\State',
                'label' => 'app.state.label',
                'placeholder' => 'app.state.choose',
                'required' => true,
                'choice_label' => function (State $state) {
                    return $state->getCountry().' - '.$state->getName();
                },
            )
        );
    }

    public function getParent()
    {
        return EntityType::class;
    }

    public function getName()
    {
        return 'app_state_selector';
    }

}
