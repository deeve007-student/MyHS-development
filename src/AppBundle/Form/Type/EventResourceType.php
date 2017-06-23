<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 31.03.2017
 * Time: 15:37
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\EventResource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\VarDumper\VarDumper;

class EventResourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'name',
            TextType::class,
            array(
                'attr' => array(
                    'placeholder' => 'app.event_resource.label',
                ),
                'label' => false,
                'required' => true,
            )
        )->add(
            'position',
            HiddenType::class,
            array(
                'label' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'app-position',
                ),
            )
        )->add(
            'default',
            CheckboxType::class,
            array(
                'label' => false,
                'required' => false,
            )
        );

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars['is_default_resource'] = $form->get('default')->getData();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\EventResource',
            )
        );
    }

    public function getName()
    {
        return 'app_event_resource';
    }

}
