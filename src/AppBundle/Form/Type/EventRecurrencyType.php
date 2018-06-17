<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 10.06.2018
 * Time: 11:47
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\EventRecurrency;
use AppBundle\Form\Traits\AddFieldOptionsTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Translation\Translator;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class EventRecurrencyType
 */
class EventRecurrencyType extends AbstractType
{

    use AddFieldOptionsTrait;

    /** @var Translator */
    protected $translator;

    /** @var array */
    protected $fieldsToClear = [];

    /** @var array */
    protected $arrayFieldsToClear = [];

    /**
     * EventRecurrencyType constructor.
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $recurrency = $event->getData();
            $form = $event->getForm();

            if (is_null($recurrency)) {
                $this->addFieldOptions($form, 'ends', [
                    'data' => 'never',
                ]);
                $this->addFieldOptions($form, 'every', [
                    'data' => 1,
                ]);
                return;
            }

            if ($recurrency instanceof EventRecurrency) {

                if (!is_null($recurrency->getDateEnd())) {
                    $this->addFieldOptions($form, 'ends', [
                        'data' => 'on',
                    ]);
                    return;
                }

                if (!is_null($recurrency->getCount())) {
                    $this->addFieldOptions($form, 'ends', [
                        'data' => 'after',
                    ]);
                    return;
                }

            }

        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {

            $data = $event->getData();

            if (array_key_exists('ends', $data)) {

                if ($data['ends'] === 'never') {
                    $this->fieldsToClear[] = 'count';
                    $this->fieldsToClear[] = 'dateEnd';
                }

                if ($data['ends'] === 'on') {
                    $this->fieldsToClear[] = 'count';
                }

                if ($data['ends'] === 'after') {
                    $this->fieldsToClear[] = 'dateEnd';
                }

                if (
                    $data['type'] !== EventRecurrency::CUSTOM
                    || ($data['type'] === EventRecurrency::CUSTOM && $data['customType'] !== EventRecurrency::WEEKLY)
                ) {
                    $this->arrayFieldsToClear[] = 'weekdays';
                }

            }
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {

            $data = $event->getData();

            if (count($this->fieldsToClear) > 0) {
                $accessor = PropertyAccess::createPropertyAccessor();
                foreach ($this->fieldsToClear as $field) {
                    $accessor->setValue($data, $field, null);
                }
            }

            if (count($this->arrayFieldsToClear) > 0) {
                $accessor = PropertyAccess::createPropertyAccessor();
                foreach ($this->arrayFieldsToClear as $field) {
                    $accessor->setValue($data, $field, []);
                }
            }

            $event->setData($data);
        });

        $builder->add(
            'type',
            ChoiceType::class,
            [
                'choices' => [
                    EventRecurrency::NO_REPEAT,
                    EventRecurrency::DAILY,
                    EventRecurrency::WEEKLY,
                    EventRecurrency::MONTHLY,
                    EventRecurrency::WEEKDAY,
                    EventRecurrency::ANNUALLY,
                    EventRecurrency::CUSTOM,
                ],
                'choices_as_values' => true,
                'choice_label' => function ($choiceValue) use ($options) {
                    return $this->translator->trans('app.recurrency.types.' . $choiceValue, [
                        '%date_weekly%' => $this->translator->trans('app.recurrency.on') . ' ' . $options['date']->format('l'),
                        '%date_monthly%' => $this->translator->trans('app.recurrency.on') . ' ' . $options['date']->format('d'),
                        '%date_annually%' => $this->translator->trans('app.recurrency.on') . ' ' . $options['date']->format('M d'),
                    ]);
                },
                'label' => 'app.recurrency.label',
                'required' => true,
                'attr' => [
                    'class' => 'event-recurrency-selector'
                ],
            ]
        )->add(
            'customType',
            ChoiceType::class,
            [
                'choices' => [
                    EventRecurrency::DAILY,
                    EventRecurrency::WEEKLY,
                    EventRecurrency::MONTHLY,
                    EventRecurrency::ANNUALLY,
                ],
                'choices_as_values' => true,
                'choice_label' => function ($choiceValue) use ($options) {
                    return $this->translator->trans('app.recurrency.custom_types.' . $choiceValue);
                },
                'label' => 'app.recurrency.label',
                'required' => true,
                'attr' => [
                    'class' => 'event-custom-recurrency-selector'
                ],
            ]
        )->add(
            'every',
            IntegerType::class,
            [
                'label' => 'app.recurrency.repeat_every',
                'required' => true,
            ]
        )->add(
            'weekdays',
            ChoiceType::class,
            [
                'label' => 'app.recurrency.repeat_on',
                'required' => false,
                'choices_as_values' => true,
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'app.task.repeat_week.mon' => 'MO',
                    'app.task.repeat_week.tue' => 'TU',
                    'app.task.repeat_week.wed' => 'WE',
                    'app.task.repeat_week.thu' => 'TH',
                    'app.task.repeat_week.fri' => 'FR',
                    'app.task.repeat_week.sat' => 'SA',
                    'app.task.repeat_week.sun' => 'SU',
                ],
            ]
        )->add(
            'ends',
            ChoiceType::class,
            [
                'mapped' => false,
                'label' => 'app.recurrency.ends',
                'required' => true,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'never',
                    'on',
                    'after',
                ],
                'choices_as_values' => true,
                'choice_label' => function ($choiceValue) use ($options) {
                    return $this->translator->trans('app.recurrency.' . $choiceValue);
                },
            ]
        )->add(
            'count',
            IntegerType::class,
            [
                'label' => 'app.recurrency.after',
                'required' => false,
            ]
        )->add(
            'dateEnd',
            DateType::class,
            [
                'label' => 'app.recurrency.on',
                'required' => false,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\EventRecurrency',
                'date' => new \DateTime(),
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_event_recurrency';
    }

}
