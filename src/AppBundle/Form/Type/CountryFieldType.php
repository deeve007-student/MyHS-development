<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 03.03.2017
 * Time: 20:26
 */

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountryFieldType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'class' => 'AppBundle\Entity\Country',
                'label' => 'myhs.country.label',
                'placeholder' => 'myhs.country.choose',
                'required' => true,
            )
        );
    }

    public function getParent()
    {
        return EntityType::class;
    }

    public function getName()
    {
        return 'myhs_country_selector';
    }

}
