<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:47
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\TreatmentNote;
use AppBundle\Entity\TreatmentNoteTemplate;
use AppBundle\Utils\Hasher;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TreatmentNoteType extends AbstractType
{

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Hasher */
    protected $hasher;

    /** @var  RequestStack */
    protected $requestStack;

    public function __construct(
        EntityManager $entityManager,
        Hasher $hasher,
        RequestStack $requestStack
    ) {
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
        $this->requestStack = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                if ($event->getData() instanceof TreatmentNote) {

                    if (!$event->getData()->getId()) {

                        $templatesArray = array_reduce(
                            $this->entityManager->getRepository('AppBundle:TreatmentNoteTemplate')->findAll(),
                            function ($result, TreatmentNoteTemplate $treatmentNoteTemplate) {
                                $result[$this->hasher->encodeObject(
                                    $treatmentNoteTemplate
                                )] = (string)$treatmentNoteTemplate;

                                return $result;
                            },
                            array()
                        );

                        $event->getForm()->add(
                            'template',
                            ChoiceType::class,
                            array(
                                'required' => true,
                                'mapped' => false,
                                'label' => 'app.treatment_note_template.choose',
                                'choices' => $templatesArray,
                                'data' => $this->hasher->encodeObject(
                                    $this->requestStack->getCurrentRequest()->get('template')
                                ),
                                //'data' => array($this->hasher->encodeObject($this->requestStack->getCurrentRequest()->get('template')) => $this->requestStack->getCurrentRequest()->get('template')),
                                //'data' => (string)($this->requestStack->getCurrentRequest()->get('template')),
                            )
                        )->add(
                            'name',
                            TextType::class,
                            array(
                                'required' => true,
                                'label' => 'app.treatment_note.name',
                                'data' => (string)($this->requestStack->getCurrentRequest()->get('template')),
                            )
                        );
                    }

                    $event->getForm()->add(
                        'patientHash',
                        HiddenType::class,
                        array(
                            'required' => false,
                            'mapped' => false,
                            'data' => $this->hasher->encodeObject($event->getData()->getPatient()),
                        )
                    );
                }
            }
        );


        $builder->add(
            'name',
            TextType::class,
            array(
                'required' => true,
                'label' => 'app.treatment_note.name',
            )
        )->add(
            'treatmentNoteFields',
            TreatmentNoteFieldsType::class,
            array(
                'label' => false,
            )
        );
    }

    public function configureOptions(
        OptionsResolver $resolver
    ) {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\TreatmentNote',
            )
        );
    }

    public function getName()
    {
        return 'app_treatment_note';
    }

}
