<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.05.2017
 * Time: 13:16
 */

namespace ReportBundle\Form\Type;

use AppBundle\Form\Type\DateType;
use AppBundle\Form\Type\TreatmentFieldType;
use AppBundle\Form\Type\TreatmentType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RevenueType extends AbstractReportType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'range',
            DateRangeType::class,
            [
                'ranges' => array(
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
        return 'app_report_revenue';
    }
}
