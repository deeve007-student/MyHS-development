<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 12:56
 */

namespace AppBundle\Form\Type;

use AppBundle\Utils\Formatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarDataType extends AbstractType
{

    protected $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'workDayStart',
            TimeType::class,
            array(
                'required' => true,
                'output_is_string' => true,
                'label' => 'app.calendar_data.work_day_start',
            )
        )->add(
            'workDayEnd',
            TimeType::class,
            array(
                'required' => true,
                'output_is_string' => true,
                'label' => 'app.calendar_data.work_day_end',
            )
        )->add(
            'timeInterval',
            IntegerType::class,
            array(
                'required' => true,
                'label' => 'app.calendar_data.work_day_interval',
            )
        )->add(
            'resources',
            EventResourcesType::class,
            array(
                'required' => true,
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\CalendarData',
                'error_mapping' => array(
                    'datesNotEqual' => 'workDayEnd',
                    'endMoreThanStart' => 'workDayEnd',
                ),
            )
        );
    }

    public function getName()
    {
        return 'app_calendar_data';
    }

}
