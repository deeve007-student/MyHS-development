<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 19:25
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Appointment;
use AppBundle\Utils\Formatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\VarDumper\VarDumper;

class AppointmentType extends AbstractType
{

    /** @var  Formatter */
    protected $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            if ($data instanceof Appointment) {
                $form->get('start')->setData($data->getStart());
                $form->get('end')->setData($data->getEnd());
                $form->get('date')->setData($data->getStart());
            }
        });

        $builder->add(
            'description',
            TextareaType::class,
            array(
                'required' => true,
                'label' => 'app.appointment.description',
            )
        )->add(
            'patient',
            PatientFieldType::class
        )->add(
            'treatment',
            TreatmentFieldType::class
        )->add(
            'date',
            DateType::class,
            [
                'label' => 'app.date',
                'mapped' => false,
            ]
        )->add(
            'start',
            TimeType::class,
            [
                'label' => 'app.appointment.start',
                'mapped' => false,
            ]
        )->add(
            'end',
            TimeType::class,
            [
                'label' => 'app.appointment.end',
                'mapped' => false,
            ]
        );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $formData = $event->getForm()->getViewData();

            $formData->setStart(new \DateTime($data['date'] . ' ' . $data['start']));
            $formData->setEnd(new \DateTime($data['date'] . ' ' . $data['end']));
            $event->getForm()->setData($formData);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Appointment',
            )
        );
    }

    public function getName()
    {
        return 'app_appointment';
    }

}
