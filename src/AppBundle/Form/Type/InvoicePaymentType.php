<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:47
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\InvoicePayment;
use AppBundle\Form\Traits\AddFieldOptionsTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoicePaymentType extends AbstractType
{

    use AddFieldOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $formEvent) {
            $data = $formEvent->getData();
            $form = $formEvent->getForm();
            if ($data instanceof InvoicePayment) {
                $this->addFieldOptions(
                    $form,
                    'amount',
                    array(
                        'data' => $data->getInvoice()->getAmountDue(),
                    )
                );
            }
        });

        $builder->add(
            'date',
            DateType::class,
            array(
                'label' => 'app.invoice_payment.date',
                'required' => true,
            )
        )->add(
            'amount',
            PriceFieldType::class,
            array(
                'label' => 'app.invoice_payment.amount',
                'attr' => array(
                    'class' => 'app-price',
                    'data-price' => true,
                ),
            )
        )->add(
            'paymentMethod',
            EntityType::class,
            array(
                'required' => true,
                'class' => 'AppBundle\Entity\InvoicePaymentMethod',
                'label' => 'app.invoice_payment_method.label_short',
                'placeholder' => 'app.invoice_payment_method.choose',
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\InvoicePayment',
                'error_mapping' => array(
                    'amountCorrect' => 'amount',
                ),
            )
        );
    }

    public function getName()
    {
        return 'app_invoice_payment';
    }

}
