<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.02.18
 * Time: 16:55
 */


namespace AppBundle\Form\Type;

use AppBundle\Twig\TreatmentNoteExtension;
use Doctrine\ORM\EntityManager;
use ReportBundle\Form\Type\DateRangeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TreatmentNoteExportType extends AbstractType
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var TreatmentNoteExtension */
    protected $treatmentNoteExtension;

    public function __construct(
        EntityManager $entityManager,
        TreatmentNoteExtension $treatmentNoteExtension
    )
    {
        $this->entityManager = $entityManager;
        $this->treatmentNoteExtension = $treatmentNoteExtension;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        //$now = new \DateTime();

        $builder->add(
            'range',
            DateRangeType::class,
            [
                'label' => false,
                'ranges' => array(
                    DateRangeType::LAST,
                    DateRangeType::CHOICE_MONTH,
                    DateRangeType::CHOICE_PREV_MONTH,
                    DateRangeType::CHOICE_QUARTER,
                    DateRangeType::CHOICE_PREV_QUARTER,
                    DateRangeType::CHOICE_NEXT_QUARTER,
                    DateRangeType::CHOICE_YEAR,
                    DateRangeType::RANGE,
                ),
                'required' => true,
                'mapped' => false,
            ]
        )->add(
            'dateStart',
            DateType::class,
            [
                //'data' => $now,
                'label' => false,
                'required' => false,
                'mapped' => false,
            ]
        )->add(
            'dateEnd',
            DateType::class,
            [
                //'data' => $now,
                'label' => false,
                'required' => false,
                'mapped' => false,
            ]
        );
    }

    public function configureOptions(
        OptionsResolver $resolver
    )
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Patient',
        ));
    }

    public function getName()
    {
        return 'app_treatment_note_export';
    }
}
