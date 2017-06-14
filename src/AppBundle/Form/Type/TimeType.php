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
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeType extends AbstractType
{

    /** @var  Formatter */
    protected $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            if (!$event->getData()) {
                $event->setData(new \DateTime());
            }
        });

        $builder->addModelTransformer(new CallbackTransformer(
            function (\DateTime $dateTime) {
                return $dateTime->format($this->formatter->getBackendTimeFormat());
            },
            function ($dateTimeString) {
                return \DateTime::createFromFormat($this->formatter->getBackendTimeFormat(), $dateTimeString);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
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
