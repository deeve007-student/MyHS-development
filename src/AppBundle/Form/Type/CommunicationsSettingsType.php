<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 12:56
 */

namespace AppBundle\Form\Type;

use AppBundle\Form\Traits\AddFieldOptionsTrait;
use AppBundle\Utils\Formatter;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;

class CommunicationsSettingsType extends AbstractType
{

    use AddFieldOptionsTrait;

    /** @var Formatter */
    protected $formatter;

    /** @var  Translator */
    protected $translator;

    /** @var  EntityManager */
    protected $entityManager;

    public function __construct(Formatter $formatter, Translator $translator, EntityManager $entityManager)
    {
        $this->formatter = $formatter;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'fromEmailAddress',
            TextType::class,
            array(
                'required' => true,
                'label' => 'app.communications_settings.from_email_address',
            )
        )->add(
            'appointmentCreationEmail',
            VariablesTextareaType::class,
            array(
                'required' => true,
                'label' => 'app.communications_settings.appointment_creation_email',
                'attr' => array(
                    'style' => 'height: 150px;',
                ),
                'variables' => array(
                    'patientName' => 'app.patient.name',
                    'appointmentDate' => 'app.patient.name',
                    'appointmentTime' => 'app.appointment.date_full',
                    'practitionerName' => 'app.appointment.time_full',
                    'businessName' => 'app.user.business_name',
                ),
            )
        )->add(
            'appointmentCreationSms',
            VariablesTextareaType::class,
            array(
                'required' => true,
                'label' => 'app.communications_settings.appointment_creation_sms',
                'attr' => array(
                    'style' => 'height: 150px;',
                ),
                'variables' => array(
                    'patientName' => 'app.patient.name',
                    'appointmentDate' => 'app.patient.name',
                    'appointmentTime' => 'app.appointment.date_full',
                    'practitionerName' => 'app.appointment.time_full',
                    'businessName' => 'app.user.business_name',
                ),
            )
        )->add(
            'appointmentReminderEmail',
            VariablesTextareaType::class,
            array(
                'required' => true,
                'label' => 'app.communications_settings.appointment_reminder_email',
                'attr' => array(
                    'style' => 'height: 150px;',
                ),
                'variables' => array(
                    'patientName' => 'app.patient.name',
                    'appointmentDate' => 'app.patient.name',
                    'appointmentTime' => 'app.appointment.date_full',
                    'practitionerName' => 'app.appointment.time_full',
                    'businessName' => 'app.user.business_name',
                ),
            )
        )->add(
            'appointmentReminderSms',
            VariablesTextareaType::class,
            array(
                'required' => true,
                'label' => 'app.communications_settings.appointment_reminder_sms',
                'attr' => array(
                    'style' => 'height: 150px;',
                ),
                'variables' => array(
                    'patientName' => 'app.patient.name',
                    'appointmentDate' => 'app.patient.name',
                    'appointmentTime' => 'app.appointment.date_full',
                    'practitionerName' => 'app.appointment.time_full',
                    'businessName' => 'app.user.business_name',
                ),
            )
        )->add(
            'recallEmailSubject',
            VariablesTextareaType::class,
            array(
                'required' => true,
                'label' => 'app.communications_settings.recall_email_subject',
                'variables' => array(
                    'patientName' => 'app.patient.name',
                    'practitionerName' => 'app.appointment.time_full',
                    'businessName' => 'app.user.business_name',
                ),
            )
        )->add(
            'recallEmail',
            VariablesTextareaType::class,
            array(
                'required' => true,
                'label' => 'app.communications_settings.recall_email',
                'attr' => array(
                    'style' => 'height: 150px;',
                ),
                'variables' => array(
                    'patientName' => 'app.patient.name',
                    'practitionerName' => 'app.appointment.time_full',
                    'businessName' => 'app.user.business_name',
                ),
            )
        )->add(
            'recallSms',
            VariablesTextareaType::class,
            array(
                'required' => true,
                'label' => 'app.communications_settings.recall_sms',
                'attr' => array(
                    'style' => 'height: 150px;',
                ),
                'variables' => array(
                    'patientName' => 'app.patient.name',
                    'practitionerName' => 'app.appointment.time_full',
                    'businessName' => 'app.user.business_name',
                ),
            )
        )->add(
            'whenRemainderEmailSentDay',
            IntegerType::class,
            array(
                'required' => true,
            )
        )->add(
            'whenRemainderEmailSentTime',
            TimeType::class,
            array(
                'required' => true,
            )
        )->add(
            'whenRemainderSmsSentDay',
            IntegerType::class,
            array(
                'required' => true,
            )
        )->add(
            'whenRemainderSmsSentTime',
            TimeType::class,
            array(
                'required' => true,
            )
        )->add(
            'noShowSubject',
            VariablesTextareaType::class,
            array(
                'required' => true,
                'label' => 'app.communications_settings.no_show_subject',
                'variables' => array(
                    'patientName' => 'app.patient.name',
                    'practitionerName' => 'app.appointment.time_full',
                    'businessName' => 'app.user.business_name',
                ),
            )
        )->add(
            'noShowEmail',
            VariablesTextareaType::class,
            array(
                'required' => true,
                'label' => 'app.communications_settings.no_show_email',
                'attr' => array(
                    'style' => 'height: 150px;',
                ),
                'variables' => array(
                    'patientName' => 'app.patient.name',
                    'practitionerName' => 'app.appointment.time_full',
                    'businessName' => 'app.user.business_name',
                    'treatmentType' => 'app.treatment.type_full',
                    'appointmentDate' => 'app.appointment.date_full',
                    'appointmentTime' => 'app.appointment.time_full',
                ),
            )
        );

        /*
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $invoiceSettings = $event->getData();
            $form = $event->getForm();
            if ($invoiceSettings instanceof InvoiceSettings) {

            }
        });

        $builder->add(
            'invoiceNumber',
            IntegerType::class,
            array(
                'required' => true,
                'label' => 'app.invoice.number_full',
            )
        )->add(
            'dueWithin',
            InvoiceDueDateFieldType::class,
            array(
                'required' => true,
            )
        )->add(
            'invoiceTitle',
            TextType::class,
            array(
                'required' => true,
            )
        )->add(
            'invoiceNotes',
            TextareaType::class,
            array(
                'required' => false,
                'label' => 'app.invoice_settings.invoice_notes',
            )
        )->add(
            'invoiceEmail',
            VariablesTextareaType::class,
            array(
                'required' => false,
                'label' => 'app.invoice_settings.invoice_email',
                'attr' => array(
                    'style' => 'height: 150px;',
                ),
                'variables' => array(
                    'invoiceNumber' => 'app.invoice.number_full',
                    'invoiceDate' => 'app.invoice.date',
                    'invoiceDueDate' => 'app.invoice.due_date_short',
                    'patientName' => 'app.patient.name',
                    'invoiceTotal' => 'app.invoice.total',
                    'businessName' => 'app.user.business_name',
                ),
            )
        );
        */
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\CommunicationsSettings',
            )
        );
    }

    public function getName()
    {
        return 'app_communications_settings';
    }

}
