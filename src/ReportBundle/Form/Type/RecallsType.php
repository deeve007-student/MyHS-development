<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.05.2017
 * Time: 13:16
 */

namespace ReportBundle\Form\Type;

use AppBundle\Form\DataTransformer\ReferrerTransformer;
use AppBundle\Form\Type\DateType;
use AppBundle\Form\Type\TreatmentFieldType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecallsType extends AbstractReportType
{

    /** @var EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'status',
            ChoiceType::class,
            array(
                'label' => 'app.report.recalls.status',
                'choices' => array(
                    'current' => 'app.report.recalls.status_current',
                    'past' => 'app.report.recalls.status_past',
                    'all' => 'app.report.recalls.status_all',
                )
            )
        )->add(
            'range',
            DateRangeType::class,
            [
                'ranges' => array(
                    DateRangeType::CHOICE_ALL,
                    DateRangeType::CHOICE_MONTH,
                    DateRangeType::CHOICE_PREV_MONTH,
                    DateRangeType::CHOICE_QUARTER,
                    DateRangeType::CHOICE_PREV_QUARTER,
                    DateRangeType::CHOICE_NEXT_QUARTER,
                    DateRangeType::CHOICE_YEAR,
                    DateRangeType::RANGE,
                ),
                'required' => true,
            ]
        )->add(
            'dateStart',
            DateType::class,
            [
                'required' => false,
            ]
        )->add(
            'dateEnd',
            DateType::class,
            [
                'required' => false,
            ]
        );

        parent::buildForm($builder, $options);

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'xls' => true,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_report_recalls';
    }
}
