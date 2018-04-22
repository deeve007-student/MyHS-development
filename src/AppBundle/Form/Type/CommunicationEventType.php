<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 05.08.2017
 * Time: 22:04
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Recall;
use AppBundle\Form\Traits\AddFieldOptionsTrait;
use AppBundle\Utils\Templater;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use UserBundle\Entity\User;

class CommunicationEventType extends AbstractType
{

    use AddFieldOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'patient',
            PatientFieldType::class,
            array(
                'required' => true,
            )
        )->add(
            'description',
            TextType::class,
            [
                'label' => 'app.communication_event.description',
                'required' => true,
            ]
        )->add(
            'date',
            DateType::class,
            [
                'label' => 'app.communication_event.date',
                'required' => true,
            ]
        )->add(
            'color',
            ColorpickerType::class,
            [
                'label' => 'app.communication_event.colour',
                'required' => true,
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\CommunicationEvent',
            )
        );
    }

    public function getName()
    {
        return 'app_communication_event';
    }

}
