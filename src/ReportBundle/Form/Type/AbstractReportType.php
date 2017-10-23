<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 05.06.2017
 * Time: 11:25
 */

namespace ReportBundle\Form\Type;

use CRM\CompanyBundle\Form\Type\CompanySelectType;
use CRM\FormBundle\Form\Type\YearMonthType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbstractReportType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['xls']) {

            $builder->add(
                'xls',
                HiddenType::class,
                array(
                    'label' => 'app.report.apply_xls',
                    'attr' => array(
                        'class' => 'app-report-xls'
                    ),
                )
            )->add(
                'submitXls',
                ButtonType::class,
                array(
                    'label' => 'app.report.xls',
                    'attr' => array(
                        'class' => 'btn btn-primary pull-left app-report-submit-xls'
                    ),
                )
            );
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'csrf_protection' => false,
                'xls' => false,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_abstract_report';
    }
}
