<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 12.03.18
 * Time: 14:17
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\InvoiceRefund;
use AppBundle\Entity\Refund;
use AppBundle\Form\Traits\AddFieldOptionsTrait;
use AppBundle\Validator\InvoiceRefundItemSumsCorrect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceRefundType extends AbstractType
{

    use AddFieldOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data instanceof Refund) {
                if ($invoice = $data->getInvoice()) {

                    $items = array();
                    foreach ($invoice->getPayments() as $invoicePayment) {

                        if (!isset($items[$invoicePayment->getPaymentMethod()->getId()])) {

                            $paid = $invoicePayment->getAmount();

                            /*
                            foreach ($invoicePayment->getInvoice()->getRefunds() as $refund) {
                                foreach ($refund->getItems() as $refundItem) {
                                    if ($refundItem->getPaymentMethod()->getId() == $invoicePayment->getPaymentMethod()->getId()) {
                                        $paid -= $refundItem->getAmount();
                                    }
                                }
                            }
                            */

                            $items[$invoicePayment->getPaymentMethod()->getId()] = array(
                                'name' => $invoicePayment->getPaymentMethod()->getName(),
                                'item' => $invoicePayment->getPaymentMethod(),
                                'amount' => 0,
                                'paid' => $paid,
                            );
                        } else {
                            $items[$invoicePayment->getPaymentMethod()->getId()]['paid'] += $invoicePayment->getPaidAmount();
                        }
                    }

                    $items = array_values($items);

                    $form->add(
                        'items',
                        CollectionType::class,
                        array(
                            'entry_type' => InvoiceRefundItemType::class,
                            'mapped' => false,
                            'label' => false,
                            'data' => $items,
                            'allow_add' => true,
                            'allow_delete' => true,
                        )
                    );

                }
            }
        });

        $builder->add(
            'items',
            CollectionType::class,
            array(
                'entry_type' => InvoiceRefundItemType::class,
                'mapped' => false,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
            )
        )->add(
            'paymentsTotal',
            PriceFieldType::class,
            array(
                'label' => false,
                //'mapped' => false,
                'data' => 0,
                'read_only' => true,
                'attr' => array(
                    'data-refund-total' => true,
                    'class' => 'app-price',
                )
            )
        );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Refund',
                'constraints' => array(
                    new InvoiceRefundItemSumsCorrect(),
                ),
            )
        );
    }

    public function getName()
    {
        return 'app_invoice_refund';
    }

}
