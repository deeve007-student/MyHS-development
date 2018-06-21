<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.05.2017
 * Time: 13:16
 */

namespace ReportBundle\Form\Type;

use AppBundle\Entity\Appointment;
use AppBundle\Form\Type\DateType;
use AppBundle\Form\Type\TreatmentFieldType;
use AppBundle\Form\Type\TreatmentType;
use AppBundle\Utils\EventUtils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientRetentionType extends AbstractReportType
{

    /** @var EventUtils */
    protected $eventUtils;

    public function __construct(EventUtils $eventUtils)
    {
        $this->eventUtils = $eventUtils;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $years = [date('Y') => date('Y')];
        if ($app = $this->eventUtils->getActiveEventsQb(Appointment::class)
            ->orderBy('a.start', 'ASC')
            ->setMaxResults(1)->getQuery()->getOneOrNullResult()) {
            $yearsTmp = range($app->getStart()->format('Y'), date('Y'));
            $years = array();
            foreach ($yearsTmp as $year) {
                $years[$year] = $year;
            }
        }

        $builder->add(
            'year',
            ChoiceType::class,
            [
                'choices' => $years,
                'required' => true,
            ]
        )->add(
            'treatment',
            TreatmentFieldType::class,
            [
                'required' => false,
                'attr' => array(
                    'class' => TreatmentFieldType::CSS_CLASS . ' wide-filter',
                )
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
        return 'app_report_patient_retention';
    }
}
