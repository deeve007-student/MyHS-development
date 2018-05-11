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

class PatientsFilterType extends AbstractReportType
{

    /** @var EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getUpcomingAppointmentTranslation($code)
    {
        switch ($code) {
            case 'noMatter':
                return 'app.report.patients.no_matter';
                break;
            case 'yes':
                return 'app.yes';
                break;
            case 'no':
                return 'app.no';
                break;
        }
        throw new \Exception('Undefined upcoming appointment value');
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'upcomingAppointment',
            ChoiceType::class,
            [
                'required' => true,
                'label' => 'app.report.patients.upcoming',
                'choices' => array(
                    'noMatter' => $this->getUpcomingAppointmentTranslation('noMatter'),
                    'yes' => $this->getUpcomingAppointmentTranslation('yes'),
                    'no' => $this->getUpcomingAppointmentTranslation('no'),
                ),
            ]
        )->add(
            'appointmentDateRange',
            DateRangeType::class,
            [
                'label' => 'app.report.patients.appointment_date',
                'ranges' => array(
                    DateRangeType::CHOICE_ALL,
                    DateRangeType::CHOICE_MONTH,
                    DateRangeType::CHOICE_NEXT_MONTH,
                    DateRangeType::CHOICE_QUARTER,
                    DateRangeType::CHOICE_NEXT_QUARTER,
                    DateRangeType::CHOICE_YEAR,
                    DateRangeType::RANGE,
                ),
                'required' => true,
            ]
        )->add(
            'appointmentDateStart',
            DateType::class,
            [
                'required' => false,
            ]
        )->add(
            'appointmentDateEnd',
            DateType::class,
            [
                'required' => false,
            ]
        )->add(
            'treatmentModality',
            TreatmentFieldType::class,
            array(
                'required' => false,
                'attr' => array(
                    'class' => TreatmentFieldType::cssClass . ' wide-filter',
                )
            )
        )->add(
            'referrer',
            TextType::class,
            array(
                'label' => 'app.patient.referrer',
                'required' => false,
                'attr' => array(
                    'class' => 'app-patient-referrer',
                    'autocomplete' => 'off',
                ),
            )
        )->add(
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
            'noFutureAppointments',
            CheckboxType::class,
            [
                'required' => false,
                'label' => 'app.report.appointments.with_no_future_appointment',
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

        $builder->get('referrer')->addModelTransformer(new ReferrerTransformer($this->entityManager));

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
        return 'app_patients_filter';
    }
}
