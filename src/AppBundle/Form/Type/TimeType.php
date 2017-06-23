<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 20:22
 */

namespace AppBundle\Form\Type;

use AppBundle\Utils\Formatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use UserBundle\Entity\User;

class TimeType extends AbstractType
{

    /** @var  Formatter */
    protected $formatter;

    /** @var  User */
    protected $user;

    public function __construct(Formatter $formatter, TokenStorage $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
        $this->formatter = $formatter;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            if (!$event->getData()) {
                $event->setData(new \DateTime());
            }
            if (is_string($event->getData())) {
                $event->setData($this->toDateTime($event->getData()));
            }
        });

        $builder->addModelTransformer(new CallbackTransformer(
            function (\DateTime $dateTime) {
                return $dateTime->format($this->formatter->getBackendTimeFormat());
            },
            function ($dateTimeString) use ($options) {
                if (!$options['output_is_string']) {
                    return \DateTime::createFromFormat($this->formatter->getBackendTimeFormat(), $dateTimeString);
                }

                return $dateTimeString;
            }
        ));
    }

    protected function toDateTime($val)
    {
        if ($val instanceof \DateTime) {
            return $val;
        }
        if (is_string($val)) {
            return \DateTime::createFromFormat($this->formatter->getBackendTimeFormat(), $val);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        /*
        $formatter = $this->get('app.formatter');
        $wdStart = \DateTime::createFromFormat($formatter->getBackendTimeFormat(), $user->getCalendarData()->getWorkDayStart());
        $wdEnd = \DateTime::createFromFormat($formatter->getBackendTimeFormat(), $user->getCalendarData()->getWorkDayEnd());

        $hours = array();
        for ($i = 0; $i < $wdEnd->diff($wdStart)->h; $i++) {
            $hours[] = (clone $wdStart)->modify('+ ' . $i . 'hours')->format($formatter->getBackendHoursFormat());
        }
        */

        $hours = array();
        for ($i = 0; $i <= 12; $i++) {
            //$hours[] = str_pad($i, 2, '0', STR_PAD_LEFT);
            $hours[] = $i;
        }

        $minutes = array_map(function ($m) {
            return str_pad($m, 2, '0', STR_PAD_LEFT);
        }, range(0, 59, 1));

        if ($options['use_interval']) {
            $minutes = array();
            for ($i = 0; $i < 60; $i = $i + (int)$this->user->getCalendarData()->getTimeInterval()) {
                $minutes[] = str_pad($i, 2, '0', STR_PAD_LEFT);
            }
        }

        $view->vars = array_replace($view->vars, array(
            'h' => $this->toDateTime($form->getData())->format('g'),
            'm' => $this->toDateTime($form->getData())->format('i'),
            'ampm' => $this->toDateTime($form->getData())->format('A'),
            'hours' => $hours,
            'minutes' => $minutes,
            'unique_id' => uniqid(),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'output_is_string' => false,
                'data_class' => null,
                'use_interval' => false,
                'attr' => array(
                    'class' => 'app-time'
                )
            )
        );
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function getName()
    {
        return 'app_time';
    }

}
