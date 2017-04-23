<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 31.03.2017
 * Time: 15:37
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\TreatmentNoteField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Constraints\NotBlank;

class TreatmentNoteFieldType extends AbstractType
{

    /** @var  Translator */
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $entity = $event->getData();
                if ($entity instanceof TreatmentNoteField) {

                    $constraints = array();
                    if ($entity->getMandatory()) {
                        $constraints = array(
                            new NotBlank(),
                        );
                    }

                    $form->add(
                        'value',
                        TextareaType::class,
                        array(
                            'label' => $entity->getName(),
                            'required' => $entity->getMandatory(),
                            'constraints' => $constraints,
                        )
                    )/*->add(
                        'notes',
                        HiddenType::class,
                        array()
                    )*/;

                }
            }
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
        return 'app_treatment_note_field';
    }

}
