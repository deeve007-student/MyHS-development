<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:47
 */

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoicePaymentType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            )
        );
    }

    public function getName()
    {
        return 'app_invoice_payment';
    }

}
