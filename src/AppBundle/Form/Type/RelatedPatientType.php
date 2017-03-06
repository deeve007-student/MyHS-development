<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 16:43
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

class RelatedPatientType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'patient',
            PatientFieldType::class,
            array(
                'label' => 'app.related_patient.label',
                'required' => true,
            )
        )->add(
            'patientRelationship',
            ChoiceType::class,
            array(
                'label' => 'app.patient_relationship.label',
                'required' => true,
                'placeholder' => 'app.patient_relationship.choose',
                'choices' => array(
                    'Parent' => 'Parent',
                    'Child' => 'Child',
                    'Sibling' => 'Sibling',
                    'Partner' => 'Partner',
                    'Spouse' => 'Spouse',
                    'Relative' => 'Relative',
                    'Other' => 'Other',
                ),
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\RelatedPatient',
            )
        );
    }

    public function getName()
    {
        return 'app_related_patient';
    }

}
