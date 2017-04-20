<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 31.03.2017
 * Time: 15:37
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;

class TreatmentNoteTemplateFieldType extends AbstractType
{

    /** @var  Translator */
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            TextType::class,
            array(
                'attr' => array(
                    'placeholder' => 'app.treatment_note_field.name',
                ),
                'label' => false,
                'required' => true,
            )
        )->add(
            'position',
            HiddenType::class,
            array(
                'label' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'app-position',
                ),
            )
        )->add(
            'mandatory',
            CheckboxType::class,
            array(
                'label' => 'app.treatment_note_field.mandatory',
            )
        )->add(
            'notes',
            TextType::class,
            array(
                'label' => false,
                'required' => false,
                'attr' => array(
                    'placeholder' => 'app.treatment_note_field.notes',
                ),
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\TreatmentNoteField',
            )
        );
    }

    public function getName()
    {
        return 'app_treatment_note_template_field';
    }

}
