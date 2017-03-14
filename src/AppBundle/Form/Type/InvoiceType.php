<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:26
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\InvoiceProduct;
use AppBundle\Entity\InvoiceTreatment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;

class InvoiceType extends AbstractType
{

    /** @var Translator */
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            TextType::class,
            array(
                'required' => true,
                'label' => 'app.invoice.number',
                'read_only' => true,
            )
        )->add(
            'date',
            DateType::class,
            array(
                'label' => 'app.invoice.date',
                'required' => false,
                'years' => range(1900, date("Y")),
            )
        )->add(
            'dueDate',
            InvoiceDueDateFieldType::class
        )->add(
            'reminderFrequency',
            ChoiceType::class,
            array(
                'label' => 'app.invoice.reminder_frequency',
                'placeholder' => 'app.invoice.reminder_frequency_no',
                'required' => false,
                'choices' => array(
                    '7' => $this->translator->trans('app.invoice.reminder_frequency_value', array('%interval%' => 7)),
                    '15' => $this->translator->trans('app.invoice.reminder_frequency_value', array('%interval%' => 15)),
                    '30' => $this->translator->trans('app.invoice.reminder_frequency_value', array('%interval%' => 30)),
                ),
            )
        )->add(
            'patient',
            PatientFieldType::class
        )->add(
            'patientAddress',
            TextareaType::class,
            array(
                'required'=>true,
                'label'=>'app.patient.patient_address',
            )
        )->add(
            'invoiceProducts',
            CollectionType::class,
            array(
                'label' => 'app.product.plural_label',
                'required' => false,
                'entry_type' => new InvoiceProductType(),
                'delete_empty' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype_data' => (new InvoiceProduct())->setQuantity(1),
            )
        )->add(
            'invoiceTreatments',
            CollectionType::class,
            array(
                'label' => 'app.treatment.plural_label',
                'required' => false,
                'entry_type' => new InvoiceTreatmentType(),
                'delete_empty' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype_data' => (new InvoiceTreatment())->setQuantity(1),
            )
        )->add(
            'notes',
            TextareaType::class,
            array(
                'required'=>true,
                'label'=>'app.invoice.notes',
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Invoice',
            )
        );
    }

    public function getName()
    {
        return 'app_invoice';
    }

}
