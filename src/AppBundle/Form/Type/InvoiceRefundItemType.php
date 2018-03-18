<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 12.03.18
 * Time: 23:48
 */


namespace AppBundle\Form\Type;

use AppBundle\Form\Traits\AddFieldOptionsTrait;
use AppBundle\Validator\InvoiceRefundItemCorrect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class InvoiceRefundItemType extends AbstractType
{

    use AddFieldOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'name',
            HiddenType::class,
            array(
                'label' => 'name',
            )
        )->add(
            'paid',
            PriceFieldType::class,
            array(
                'label' => 'paid',
            )
        )->add(
            'amount',
            PriceFieldType::class,
            array(
                'label' => 'amount',
                'constraints' => array(
                    new Range(array(
                        'min' => 0,
                    )),
                ),
                'attr'=>array(
                    'data-item-amount' => true,
                    'class' => 'app-price',
                )
            )
        );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'label' => false,
                'error_bubbling' => false,
                'constraints' => array(
                    new InvoiceRefundItemCorrect(),
                ),
            )
        );
    }

    public function getName()
    {
        return 'app_invoice_refund_item';
    }

}
