<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 10.06.2018
 * Time: 11:47
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\EventRecurrency;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;

/**
 * Class EventRecurrencyType
 */
class EventRecurrencyType extends AbstractType
{

    /** @var Translator */
    protected $translator;

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
                'attr'=>[
                    'class' => 'event-recurrency-selector'
                ],
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
