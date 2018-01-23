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

class RecallType extends AbstractType
{

    use AddFieldOptionsTrait;

    /** @var Templater */
    protected $templater;

    /** @var TokenStorage */
    protected $tokenStorage;

    public function __construct(
        TokenStorage $tokenStorage,
        Templater $templater
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->templater = $templater;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $recall = $event->getData();
            $form = $event->getForm();

            if ($recall instanceof Recall) {
                if (!$recall->getId()) {
                    /** @var User $user */
                    $user = $this->tokenStorage->getToken()->getUser();
                    $recall->setOwner($user);

                    $this->addFieldOptions($form, 'message', array(
                        'data' => $this->templater->compile($user->getCommunicationsSettings()->getRecallEmail(), array(
                            'entity' => $recall,
                            'businessName' => $user->getBusinessName(),
                        ))
                    ));

                    $this->addFieldOptions($form, 'sms', array(
                        'data' => $this->templater->compile($user->getCommunicationsSettings()->getRecallSms(), array(
                            'entity' => $recall,
                            'businessName' => $user->getBusinessName(),
                        ))
                    ));
                }
            }
        });

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
                'choice_attr' => function (\AppBundle\Entity\RecallType $type, $key, $index) {
                    return [
                        'data-type' => mb_strtolower($type),
                    ];
                },
            ]
        )->add(
            'recallFor',
            EntityType::class,
            [
                'label' => 'app.recall_for.label',
                'required' => false,
                'class' => 'AppBundle\Entity\RecallFor',
            ]
        )->add(
            'sms',
            TextareaType::class,
            [
                'label' => 'app.recall.sms',
                'required' => false,
            ]
        )->add(
            'subject',
            TextType::class,
            [
                'label' => 'app.recall.subject',
                'required' => false,
            ]
        )->add(
            'message',
            TextareaType::class,
            [
                'label' => 'app.recall.body_message',
                'required' => false,
                'attr' => array(
                    'rows' => 5,
                ),
            ]
        )->add(
            'notes',
            TextareaType::class,
            [
                'label' => 'app.recall.notes',
                'required' => false,
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
