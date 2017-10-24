<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.05.2017
 * Time: 13:16
 */

namespace ReportBundle\Form\Type;

use AppBundle\Form\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoicesType extends AbstractReportType
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
                'label' => 'app.invoice.due_date',
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
                'label' => 'app.report.invoices.due_date_start',
                'required' => false,
            ]
        )->add(
            'dateEnd',
            DateType::class,
            [
                'label' => 'app.report.invoices.due_date_end',
                'required' => false,
            ]
        )->add(
            'paidRange',
            DateRangeType::class,
            [
                'label' => 'app.invoice.date_paid',
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
            'paidStart',
            DateType::class,
            [
                'label' => 'app.report.invoices.paid_date_start',
                'required' => false,
            ]
        )->add(
            'paidEnd',
            DateType::class,
            [
                'label' => 'app.report.invoices.paid_date_end',
                'required' => false,
            ]
        )->add(
            'productsOnly',
            CheckboxType::class,
            [
                'required' => false,
                'label' => 'app.report.invoices.products_only',
                'attr' => array(
                    'class' => 'report-checkbox'
                ),
            ]
        )->add(
            'unpaid',
            CheckboxType::class,
            [
                'required' => false,
                'label' => 'app.report.invoices.unpaid',
                'attr' => array(
                    'class' => 'report-checkbox'
                ),
            ]
        )->add(
            'unpaidRange',
            DateRangeType::class,
            [
                'label' => 'app.report.invoices.unpaid_date',
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
            'unpaidStart',
            DateType::class,
            [
                'required' => false,
            ]
        )->add(
            'unpaidEnd',
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
                'xls' => false,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_report_invoices';
    }
}
