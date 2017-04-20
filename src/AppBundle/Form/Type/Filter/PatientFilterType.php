<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.03.2017
 * Time: 13:22
 */

namespace AppBundle\Form\Type\Filter;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PatientFilterType extends FilterType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'string',
            TextType::class,
            array(
                'required' => false,
                'label' => false,
                'attr' => array('placeholder' => 'app.patient.filter.string'),
            )
        );

    }

    public function getParent()
    {
        return 'app_filter';
    }

    public function getName()
    {
        return 'app_patient_filter';
    }

}
