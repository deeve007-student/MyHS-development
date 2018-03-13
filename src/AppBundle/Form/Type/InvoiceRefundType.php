<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 12.03.18
 * Time: 14:17
 */

namespace AppBundle\Form\Type;

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
                    foreach (array_merge(
                                 $invoice->getInvoiceProducts()->toArray(),
                                 $invoice->getInvoiceTreatments()->toArray()
                             ) as $invoiceItem) {
                        $items[] = array(
                            'name' => $invoiceItem,
                            'item' => $invoiceItem,
                            'amount' => 0,
                            'paid' => $invoiceItem->getPaidAmount(),
                        );
                    }

                    $form->add(
                        'items',
                        CollectionType::class,
                        array(
                            'entry_type' => InvoiceRefundItemType::class,
                            'mapped' => false,
                            'label' => false,
                            'data' => $items,
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
            )
        )->add(
            'itemsTotal',
            TextType::class,
            array(
                'data' => 0,
            )
        )->add(
            'paymentsTotal',
            TextType::class,
            array(
                'data' => 0,
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
