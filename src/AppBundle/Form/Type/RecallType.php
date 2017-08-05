<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 05.08.2017
 * Time: 22:04
 */

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecallType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'date',
            DateType::class,
            [
                'label' => 'app.recall.date',
                'required' => true,
            ]
        )->add(
            'text',
            TextType::class,
            [
                'label' => 'app.recall.text',
                'required' => false,
            ]
        )->add(
            'recallType',
            EntityType::class,
            [
                'label' => 'app.recall_type.label',
                'required' => true,
                'placeholder' => 'app.recall_type.choose',
                'class' => 'AppBundle\Entity\RecallType',
            ]
        )->add(
            'recallFor',
            EntityType::class,
            [
                'label' => 'app.recall_for.label',
                'required' => false,
                'class' => 'AppBundle\Entity\RecallFor',
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Recall',
            )
        );
    }

    public function getName()
    {
        return 'app_recall';
    }

}
