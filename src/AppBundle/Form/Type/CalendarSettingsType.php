<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 12:56
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\CalendarSettings;
use AppBundle\Utils\Formatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;

class CalendarSettingsType extends AbstractType
{

    /** @var Formatter */
    protected $formatter;

    /** @var  Translator */
    protected $translator;

    public function __construct(Formatter $formatter, Translator $translator)
    {
        $this->formatter = $formatter;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            if ($event->getData() instanceof CalendarSettings) {
                $event->getForm()
                    ->add(
                        'resources',
                        ChoiceType::class,
                        array(
                            'mapped' => false,
                            'required' => true,
                            'label' => 'app.event_resource.columns_per_day',
                            'choices' => array(
                                '1' => $this->translator->trans('app.event_resource.columns_single'),
                                '2' => $this->translator->trans('app.event_resource.columns_double'),
                            ),
                            'data' => $event->getData()->getResources()->count(),
                        )
                    );
            }
        });

        $builder->add(
            'workDayStart',
            TimeType::class,
            array(
                'required' => true,
                'output_is_string' => true,
                'label' => 'app.calendar_settings.work_day_start',
            )
        )->add(
            'workDayEnd',
            TimeType::class,
            array(
                'required' => true,
                'output_is_string' => true,
                'label' => 'app.calendar_settings.work_day_end',
            )
        )->add(
            'timeInterval',
            ChoiceType::class,
            array(
                'required' => true,
                'label' => 'app.calendar_settings.work_day_interval',
                'choices' => array(
                    '15' => '15',
                    '30' => '30',
                    '45' => '45',
                    '60' => '60',
                ),
            )
        )/*->add(
            'resources',
            EventResourcesType::class,
            array(
                'required' => true,
            )
        )*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\CalendarSettings',
                'error_mapping' => array(
                    'datesNotEqual' => 'workDayEnd',
                    'endMoreThanStart' => 'workDayEnd',
                ),
            )
        );
    }

    public function getName()
    {
        return 'app_calendar_settings';
    }

}
