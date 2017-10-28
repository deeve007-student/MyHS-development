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

class PatientsType extends AbstractReportType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'withRecall',
            CheckboxType::class,
            [
                'required' => false,
                'label' => 'app.report.patients.with_recall',
                'attr' => array(
                    'class' => 'report-checkbox'
                ),
            ]
        )->add(
            'recallDateRange',
            DateRangeType::class,
            [
                'label' => 'app.report.patients.with_recall_date',
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
            'recallDateStart',
            DateType::class,
            [
                'required' => false,
            ]
        )->add(
            'recallDateEnd',
            DateType::class,
            [
                'required' => false,
            ]
        )->add(
            'upcomingAppointment',
            CheckboxType::class,
            [
                'required' => false,
                'label' => 'app.report.patients.upcoming',
                'attr' => array(
                    'class' => 'report-checkbox'
                ),
            ]
        )->add(
            'upcomingBirthday',
            CheckboxType::class,
            [
                'required' => false,
                'label' => 'app.report.patients.upcoming_birthday',
                'attr' => array(
                    'class' => 'report-checkbox'
                ),
            ]
        )->add(
            'upcomingBirthdayDateRange',
            DateRangeType::class,
            [
                'label' => 'app.report.patients.upcoming_birthday_date',
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
            'upcomingBirthdayDateStart',
            DateType::class,
            [
                'required' => false,
            ]
        )->add(
            'upcomingBirthdayDateEnd',
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
        return 'app_report_patients';
    }
}
