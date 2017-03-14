<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 13:21
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Treatment;
use AppBundle\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TreatmentFieldType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'class' => 'AppBundle\Entity\Treatment',
                'label' => 'app.treatment.label',
                'placeholder' => 'app.treatment.choose',
                'required' => true,
                'choice_attr' => function (Treatment $treatment, $key, $index) {
                    return ['data-price' => $treatment->getPrice() ];
                },
                'attr' => array(
                    'class'=>'app-treatment-selector'
                )
            )
        );
    }

    public function getParent()
    {
        return EntityType::class;
    }

    public function getName()
    {
        return 'app_treatment_selector';
    }

}
