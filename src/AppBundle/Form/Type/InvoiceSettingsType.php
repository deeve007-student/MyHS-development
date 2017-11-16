<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 12:56
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\InvoiceSettings;
use AppBundle\Form\Traits\AddFieldOptionsTrait;
use AppBundle\Utils\Formatter;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;

class InvoiceSettingsType extends AbstractType
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
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\InvoiceSettings',
            )
        );
    }

    public function getName()
    {
        return 'app_invoice_settings';
    }

}
