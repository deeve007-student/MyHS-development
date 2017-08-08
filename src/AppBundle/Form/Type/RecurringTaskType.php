<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 05.08.2017
 * Time: 22:04
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\RecurringTask;
use AppBundle\Form\Traits\AddFieldOptionsTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;

class RecurringTaskType extends AbstractType
{

    use AddFieldOptionsTrait;

    /** @var  Translator */
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            if ($data instanceof RecurringTask) {

                if (!$data->getRepeatMonth()) {
                    $this->addFieldOptions(
                        $event->getForm(),
                        'repeatMonth',
                        array(
                            'data' => RecurringTask::REPEAT_MONTH_DAY_OF_MONTH,
                        )
                    );
                }

                $event->getForm()->add(
                    'standardOrRecurring',
                    ChoiceType::class,
                    array(
                        'label' => 'app.task.standard_or_recurring.label',
                        'mapped' => false,
                        'data' => $data->getRepeats()==RecurringTask::REPEATS_ONCE ? RecurringTask::TYPE_STANDARD : RecurringTask::TYPE_RECURRING,
                        'choices' => array(
                            RecurringTask::TYPE_STANDARD => $this->translator->trans('app.task.standard_or_recurring.' . RecurringTask::TYPE_STANDARD),
                            RecurringTask::TYPE_RECURRING => $this->translator->trans('app.task.standard_or_recurring.' . RecurringTask::TYPE_RECURRING),
                        )
                    )
                );
            }
        });

        $weeks = array();
        for ($n = 1; $n <= 10; $n++) {
            $weeks[$n] = $n . ' ' . ($n == 1 ? $this->translator->trans('app.task.week') : $this->translator->trans('app.task.weeks'));
        }

        $monthes = array();
        for ($n = 1; $n <= 10; $n++) {
            $monthes[$n] = $n . ' ' . ($n == 1 ? $this->translator->trans('app.task.month') : $this->translator->trans('app.task.months'));
        }

        $years = array();
        for ($n = 1; $n <= 10; $n++) {
            $years[$n] = $n . ' ' . ($n == 1 ? $this->translator->trans('app.task.year') : $this->translator->trans('app.task.years'));
        }

        $builder->add(
            'date',
            DateType::class,
            [
                'label' => 'app.task.date',
                'required' => false,
                'mapped' => false,
            ]
        )->add(
            'startDate',
            DateType::class,
            [
                'label' => 'app.task.start_date',
                'required' => true,
            ]
        )->add(
            'text',
            TextType::class,
            [
                'label' => 'app.task.text',
                'required' => false,
            ]
        )->add(
            'repeats',
            ChoiceType::class,
            [
                'label' => 'app.task.repeats.label',
                'required' => true,
                'choices' => array(
                    RecurringTask::REPEATS_ONCE => $this->translator->trans('app.task.repeats.' . RecurringTask::REPEATS_ONCE),
                    RecurringTask::REPEATS_WEEKLY => $this->translator->trans('app.task.repeats.' . RecurringTask::REPEATS_WEEKLY),
                    RecurringTask::REPEATS_MONTHLY => $this->translator->trans('app.task.repeats.' . RecurringTask::REPEATS_MONTHLY),
                    RecurringTask::REPEATS_YEARLY => $this->translator->trans('app.task.repeats.' . RecurringTask::REPEATS_YEARLY),
                )
            ]
        )->add(
            'intervalWeek',
            ChoiceType::class,
            [
                'label' => 'app.task.repeat_every',
                'required' => true,
                'choices' => $weeks,
            ]
        )->add(
            'intervalMonth',
            ChoiceType::class,
            [
                'label' => 'app.task.repeat_every',
                'required' => true,
                'choices' => $monthes,
            ]
        )->add(
            'intervalYear',
            ChoiceType::class,
            [
                'label' => 'app.task.repeat_every',
                'required' => true,
                'choices' => $years,
            ]
        )->add(
            'repeatDays',
            ChoiceType::class,
            [
                'label' => 'app.task.repeat_week.label',
                'required' => true,
                'expanded' => true,
                'multiple' => true,
                'choices' => array(
                    0 => $this->translator->trans('app.task.repeat_week.mon'),
                    1 => $this->translator->trans('app.task.repeat_week.tue'),
                    2 => $this->translator->trans('app.task.repeat_week.wed'),
                    3 => $this->translator->trans('app.task.repeat_week.thu'),
                    4 => $this->translator->trans('app.task.repeat_week.fri'),
                    5 => $this->translator->trans('app.task.repeat_week.sat'),
                    6 => $this->translator->trans('app.task.repeat_week.sun'),
                ),
            ]
        )->add(
            'repeatMonth',
            ChoiceType::class,
            [
                'label' => 'app.task.repeat_month.label',
                'required' => true,
                'expanded' => true,
                'multiple' => false,
                'choices' => array(
                    RecurringTask::REPEAT_MONTH_DAY_OF_MONTH => $this->translator->trans('app.task.repeat_month.' . RecurringTask::REPEAT_MONTH_DAY_OF_MONTH),
                    RecurringTask::REPEAT_MONTH_DAY_OF_WEEK => $this->translator->trans('app.task.repeat_month.' . RecurringTask::REPEAT_MONTH_DAY_OF_WEEK),
                ),
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\RecurringTask',
            )
        );
    }

    public function getName()
    {
        return 'app_recurring_task';
    }

}
