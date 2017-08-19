<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:47
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\TreatmentNote;
use AppBundle\Entity\TreatmentNoteTemplate;
use AppBundle\Utils\Formatter;
use AppBundle\Utils\Hasher;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;

class TreatmentNoteType extends AbstractType
{

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Hasher */
    protected $hasher;

    /** @var  RequestStack */
    protected $requestStack;

    /** @var  Formatter */
    protected $formatter;

    /** @var  Translator */
    protected $translator;

    public function __construct(
        EntityManager $entityManager,
        Hasher $hasher,
        RequestStack $requestStack,
        Formatter $formatter,
        Translator $translator
    )
    {
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
        $this->requestStack = $requestStack;
        $this->formatter = $formatter;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                if ($event->getData() instanceof TreatmentNote) {

                    $this->addAppointmentField($event->getForm(), $event->getData()->getPatient());

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
            'status',
            ChoiceType::class,
            array(
                'required' => true,
                'label' => 'app.treatment_note.status',
                'choices' => array(
                    TreatmentNote::STATUS_DRAFT => $this->translator->trans('app.treatment_note.statuses.' . TreatmentNote::STATUS_DRAFT),
                    TreatmentNote::STATUS_FINAL => $this->translator->trans('app.treatment_note.statuses.' . TreatmentNote::STATUS_FINAL),
                )
            )
        )->add(
            'treatmentNoteFields',
            TreatmentNoteFieldsType::class,
            array(
                'label' => false,
            )
        );

        $this->addAppointmentField($builder);

    }

    protected function addAppointmentField($form, $patient = null)
    {
        $form->add(
            'appointment',
            EntityType::class,
            array(
                'required' => false,
                'label' => 'app.appointment.label',
                'placeholder' => 'app.appointment.choose',
                'class' => 'AppBundle\Entity\Appointment',
                'query_builder' => function (EntityRepository $repository) use ($patient) {
                    $qb = $repository->createQueryBuilder('a')
                        ->orderBy('a.start', 'DESC');

                    if ($patient) {
                        $qb->where('a.patient = :patient')
                            ->setParameter('patient', $patient);
                    }

                    return $qb;
                },
                'choice_label' => function (Appointment $appointment) {
                    return
                        $appointment->getStart()->format($this->formatter->getBackendDateAndWeekDayFormat()) . ' ' . $appointment->getStart()->format($this->formatter->getBackendTimeFormat()) . ' - ' . $appointment->getPatient() . ' - ' . $appointment->getTreatment();
                }
            )
        );
    }

    public function configureOptions(
        OptionsResolver $resolver
    )
    {
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
