<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 20.04.2017
 * Time: 17:13
 */

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;

class TreatmentNoteTemplateFieldsType extends AbstractType
{

    /** @var  Translator */
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'label' => 'app.treatment_note_template.fields',
                'required' => false,
                'entry_type' => new TreatmentNoteTemplateFieldType($this->translator),
                'delete_empty' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            )
        );
    }

    public function getParent()
    {
        return CollectionType::class;
    }

    public function getName()
    {
        return 'app_treatment_note_template_fields';
    }

}
