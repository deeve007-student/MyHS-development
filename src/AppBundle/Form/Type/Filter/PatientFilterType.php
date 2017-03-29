<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.03.2017
 * Time: 13:22
 */

namespace AppBundle\Form\Type\Filter;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientFilterType extends FilterType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($builder, $options) {

                $form = $event->getForm();

                $form->add(
                    'string',
                    TextType::class,
                    array(
                        'required' => false,
                        'label' => 'app.product.label',
                        'data' => $this->getFilterValue($form, 'string'),
                        'attr' => array('placeholder' => 'app.patient.filter.string'),
                    )
                );

                parent::buildForm($builder, $options);

            }
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