<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 16:48
 */

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientFieldType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'class' => 'AppBundle\Entity\Patient',
                'label' => 'app.patient.label',
                'placeholder' => 'app.patient.choose',
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
        return 'app_patient_selector';
    }

}
