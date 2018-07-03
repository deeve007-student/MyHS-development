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
use AppBundle\Utils\EventUtils;
use AppBundle\Utils\Formatter;
use AppBundle\Utils\Hasher;
use AppBundle\Utils\TreatmentNoteUtils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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

    /** @var  TreatmentNoteUtils */
    protected $treatmentNoteUtils;

    /** @var  EventUtils */
    protected $eventUtils;

    public function __construct(
        EntityManager $entityManager,
        Hasher $hasher,
        RequestStack $requestStack,
        Formatter $formatter,
        Translator $translator,
        TreatmentNoteUtils $treatmentNoteUtils,
        EventUtils $eventUtils
    )
    {
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
        $this->requestStack = $requestStack;
        $this->formatter = $formatter;
        $this->translator = $translator;
        $this->treatmentNoteUtils = $treatmentNoteUtils;
        $this->eventUtils = $eventUtils;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                if ($event->getData() instanceof TreatmentNote) {

                    /** @var TreatmentNote $treatmentNote */
                    $treatmentNote = $event->getData();

                    $currentNote = $event->getData()->getId() ? $event->getData() : null;

                    $prevTn = $this->treatmentNoteUtils->getLastFinalNoteByPatient($treatmentNote->getPatient(), $currentNote);

                    if ($prevTn) {
                        $event->getForm()->add(
                            'treatmentNoteFields',
                            TreatmentNoteFieldsType::class,
                            array(
                                'label' => false,
                                'entry_options' => array(
                                    'prev_note' => $prevTn,
                                ),
                            )
                        );
                    }

                    $this->addAppointmentField($event->getForm(), $event->getData()->getPatient());

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

                    if ($patient = $treatmentNote->getPatient()) {
                        if ($lastPatientTreatmentNote = $this->treatmentNoteUtils->getLastFinalNoteByPatient($patient)) {
                            $label = $this->translator->trans('app.treatment_note.copy');
                            $copyElement = array('copy' => $label);
                            $templatesArray = array_merge($copyElement, $templatesArray);
                        }
                    }

                    $event->getForm()->add(
                        'template',
                        ChoiceType::class,
                        array(
                            'required' => true,
                            'mapped' => false,
                            'label' => 'app.treatment_note_template.choose',
                            'choices' => $templatesArray,
                            'data' => $this->hasher->encodeObject(
                                $treatmentNote->getTemplate()
                            ),
                        )
                    );

                }
            }
        );


        $builder->add(
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
                    $qb = $this->eventUtils->getActiveEventsQb(Appointment::class)
                        ->leftJoin('a.appointmentPatients','appointmentPatient')
                        ->orderBy('a.start', 'DESC');

                    if ($patient) {
                        $qb->andWhere('appointmentPatient.patient = :patient')
                            ->setParameter('patient', $patient);
                    }

                    return $qb;
                },
                'choice_label' => function (Appointment $appointment) use ($patient) {
                    return
                        $appointment->getStart()->format($this->formatter->getBackendDateAndWeekDayFormat()) . ' ' . $appointment->getStart()->format($this->formatter->getBackendTimeFormat()) . ' - ' . $patient . ' - ' . $appointment->getTreatment();
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
