<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 05.08.2017
 * Time: 22:04
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\CommunicationType;
use AppBundle\Entity\ManualCommunication;
use AppBundle\Form\Traits\AddFieldOptionsTrait;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ManualCommunicationType extends AbstractType
{

    use AddFieldOptionsTrait;

    /** @var EntityManager */
    protected $entityManager;

    public function __construct(
        EntityManager $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data instanceof ManualCommunication) {

                if ($data->getId()) {
                    $this->addFieldOptions($form, 'sms', array(
                        'disabled' => true,
                        'read_only' => true,
                    ));
                    $this->addFieldOptions($form, 'subject', array(
                        'disabled' => true,
                        'read_only' => true,
                    ));
                    $this->addFieldOptions($form, 'message', array(
                        'disabled' => true,
                        'read_only' => true,
                    ));
                    $form->remove('file');
                }

            }
        });

        $builder->add(
            'communicationType',
            EntityType::class,
            [
                'label' => 'app.communication_type.label_short',
                'required' => true,
                'class' => 'AppBundle\Entity\CommunicationType',
                'choice_attr' => function (CommunicationType $type, $key, $index) {
                    return [
                        'data-type' => mb_strtolower($type),
                    ];
                },
            ]
        )->add(
            'patient',
            PatientFieldType::class,
            array(
                'required' => false,
                'placeholder' => 'app.patient.choose',
            )
        )->add(
            'sms',
            TextareaType::class,
            [
                'label' => 'app.message.sms',
                'required' => false,
            ]
        )->add(
            'subject',
            TextType::class,
            [
                'label' => 'app.message.subject',
                'required' => false,
            ]
        )->add(
            'message',
            TextareaType::class,
            [
                'label' => 'app.message.body_message',
                'required' => false,
                'attr' => array(
                    'rows' => 5,
                ),
            ]
        )->add(
            'file',
            FileType::class,
            array(
                'label' => 'app.attachment.plural_label',
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\ManualCommunication',
                'validation_groups' => array('Submit'),
                'error_mapping' => array(
                    'addresseeCorrect' => 'patient',
                )
            )
        );
    }

    public function getName()
    {
        return 'app_manual_communication';
    }

}
