<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 19:25
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Appointment;
use AppBundle\Form\Traits\AddFieldOptionsTrait;
use AppBundle\Form\Traits\EventTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AppointmentType
 */
class AppointmentType extends EventType
{

    use EventTrait;
    use AddFieldOptionsTrait;

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addEventSetListener($builder);
        $this->addEventBasicFields($builder, $this->eventUtils, $this->translator);

        $builder
//            ->add(
//                'selectOrCreatePatient',
//                TextType::class,
//                array(
//                    'mapped' => false,
//                    'data' => 'select',
//                )
//            )
//            ->add(
//                'patient',
//                PatientFieldType::class
//            )
            ->add(
                'appointmentPatients',
                CollectionType::class,
                array(
                    'label' => 'app.patient.plural_label_optional',
                    'required' => true,
                    'entry_type' => new AppointmentPatientType(),
                    'delete_empty' => true,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'error_bubbling' => false,
                )
            )->add(
                'treatment',
                TreatmentFieldType::class
            )->add(
                'packId',
                IntegerType::class,
                array(
                    'required' => false,
                )
            );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

//            if (empty($data['patient']) && $data['selectOrCreatePatient'] == 'new') {
//                $this->isNew = true;
//                $form->add(
//                    'newPatient',
//                    PatientCompactType::class,
//                    array(
//                        'required' => true,
//                        'mapped' => true,
//                        'property_path' => 'patient',
//                    )
//                );
//            }
        });

        $this->addEventSubmitListener($builder);
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Appointment',
                'validation_groups' => array(
                    'Appointment'
                ),
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_appointment';
    }

}
