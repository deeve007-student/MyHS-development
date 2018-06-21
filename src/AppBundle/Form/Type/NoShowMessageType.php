<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 05.08.2017
 * Time: 22:04
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\CommunicationType;
use AppBundle\Entity\NoShowMessage;
use AppBundle\Form\Traits\AddFieldOptionsTrait;
use AppBundle\Utils\Templater;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * Class NoShowMessageType
 */
class NoShowMessageType extends AbstractType
{

    use AddFieldOptionsTrait;

    /** @var EntityManager */
    protected $entityManager;

    /** @var Templater */
    protected $templater;

    /** @var TokenStorage */
    protected $tokenStorage;

    /**
     * NoShowMessageType constructor.
     * @param EntityManager $entityManager
     * @param Templater $templater
     * @param AuthorizationChecker $authorizationChecker
     */
    public function __construct(
        EntityManager $entityManager,
        Templater $templater,
        TokenStorage $tokenStorage
    )
    {
        $this->entityManager = $entityManager;
        $this->templater = $templater;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data instanceof NoShowMessage) {

                $subject = $this->templater->compile($this->tokenStorage->getToken()->getUser()->getCommunicationsSettings()->getNoShowSubject(), []);

                $body = $this->templater->compile($this->tokenStorage->getToken()->getUser()->getCommunicationsSettings()->getNoShowEmail(), [
                    'entity' => $data->getAppointment(),
                ]);

                if ($data->getSubject() == '') {
                    $data->setSubject($subject);
                }
                if ($data->getMessage() == '') {
                    $data->setMessage($body);
                }
                if ($data->getSms() == '') {
                    $data->setSms($body);
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
        );
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\NoShowMessage',
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_no_show_message';
    }

}
