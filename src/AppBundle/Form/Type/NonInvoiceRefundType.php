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
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotEqualTo;
use Symfony\Component\Validator\Constraints\Range;

class NonInvoiceRefundType extends AbstractType
{

    use AddFieldOptionsTrait;

    protected function addFields($form, Refund $refund = null)
    {
        $amountOptions = array(
            'label' => 'app.non_invoice_refund.amount',
            'mapped' => false,
            'attr' => array(
                'class' => 'app-price',
                'data-price' => true,
            ),
            'constraints' => array(
                new Range(array('min' => 0)),
                new NotEqualTo(array('value' => 0)),
            )
        );

        $paymentMethodOptions = array(
            'required' => true,
            'class' => 'AppBundle\Entity\InvoicePaymentMethod',
            'label' => 'app.non_invoice_refund.method',
            'placeholder' => 'app.invoice_payment_method.choose',
            'mapped' => false,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('pm')
                    ->where('pm.name != :hicaps')
                    ->setParameter('hicaps', 'Hicaps');
            },
            'constraints' => array(
                new NotBlank(),
            )
        );

        if ($refund) {
            $amountOptions['data'] = $refund->getItems()->first()->getAmount();
            $paymentMethodOptions['data'] = $refund->getItems()->first()->getPaymentMethod();
        }

        $form->add(
            'amount',
            PriceFieldType::class,
            $amountOptions
        )->add(
            'paymentMethod',
            EntityType::class,
            $paymentMethodOptions
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data instanceof Refund) {
                if ($data->getId()) {
                    $this->addFields($form, $data);
                }
            }
        });

        $builder->add(
            'reason',
            TextType::class,
            array(
                'label' => 'app.non_invoice_refund.reason',
                'constraints' => array(
                    new NotBlank(),
                )
            )
        );

        $this->addFields($builder);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Refund',
            )
        );
    }

    public function getName()
    {
        return 'app_non_invoice_refund';
    }

}
