<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:26
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoicePayment;
use AppBundle\Entity\InvoiceProduct;
use AppBundle\Entity\InvoiceTreatment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;
use Symfony\Component\VarDumper\VarDumper;

class InvoiceType extends AbstractType
{

    /** @var EntityManager */
    protected $entityManager;

    /** @var Translator */
    protected $translator;

    public function __construct(EntityManager $entityManager, Translator $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $invoice = $event->getData();

            if ($invoice instanceof Invoice) {

                $payments = $invoice->getPayments();

                foreach ($this->entityManager->getRepository('AppBundle:InvoicePaymentMethod')->findAll() as $method) {
                    $payment = new InvoicePayment();
                    $payment->setPaymentMethod($method)
                        ->setAmount(0)
                        ->setInvoice($invoice);
                }
            }

        });

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
                'required' => false,
                'label' => 'app.patient.patient_address',
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
            'payments',
            CollectionType::class,
            array(
                'label' => 'app.invoice_payment.plural_label_short',
                'required' => false,
                'entry_type' => new InvoicePaymentType(true),
                'delete_empty' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            )
        )->add(
            'notes',
            TextareaType::class,
            array(
                'required' => false,
                'label' => 'app.invoice.notes',
            )
        );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) {
            $data = $formEvent->getData();

                foreach ($data['payments'] as $n=>$payment) {
                    if ($payment['amount'] == 0) {
                        unset($data['payments'][$n]);
                    }
                }

            $formEvent->setData($data);
        });
    }

    public
    function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Invoice',
            )
        );
    }

    public
    function getName()
    {
        return 'app_invoice';
    }

}
