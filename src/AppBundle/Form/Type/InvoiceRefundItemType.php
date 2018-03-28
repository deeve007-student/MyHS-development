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
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class InvoiceRefundItemType extends AbstractType
{

    use AddFieldOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            if (is_array($event->getData()) && $event->getData()['name'] == 'Hicaps') {
                $event->getForm()->add(
                    'amount',
                    HiddenType::class,
                    array()
                );
            }
        });

        $builder->add(
            'name',
            HiddenType::class,
            array(
                'label' => 'name',
            )
        )->add(
            'item', EntityType::class,
            array(
                'required' => true,
                'class' => 'AppBundle\Entity\InvoicePaymentMethod',
                'label' => 'app.refund.payment_method',
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('pm')
                        ->where('pm.name != :hicaps')
                        ->setParameter('hicaps', 'Hicaps');
                },
            )
        )->add(
            'paid',
            PriceFieldType::class,
            array(
                'label' => 'paid',
                'disabled' => true,
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
                'attr' => array(
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
                'constraints' => array(//new InvoiceRefundItemCorrect(),
                ),
            )
        );
    }

    public function getName()
    {
        return 'app_invoice_refund_item';
    }

}
