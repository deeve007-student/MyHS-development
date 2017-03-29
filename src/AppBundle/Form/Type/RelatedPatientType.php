<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 16:43
 */

namespace AppBundle\Form\Type;

use AppBundle\Utils\Hasher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RelatedPatientType extends AbstractType
{

    /** @var  Hasher */
    protected $hasher;

    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

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
            EntityType::class,
            array(
                'label' => 'app.patient_relationship.label',
                'required' => true,
                'placeholder' => 'app.patient_relationship.choose',
                'class' => 'AppBundle\Entity\PatientRelationship',
                'choice_value' => $this->hasher->choiceValueCallback(),
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
