<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 17:11
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\InvoiceTreatment;
use AppBundle\Form\Traits\AddFieldOptionsTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceTreatmentType extends AbstractType
{

    use AddFieldOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                if ($data instanceof InvoiceTreatment) {
                    $this->addFieldOptions(
                        $form,
                        'total',
                        array(
                            'data' => $data->getTotal(),
                        )
                    );
                }
            }
        );

        $builder->add(
            'treatment',
            TreatmentFieldType::class
        )->add(
            'price',
            PriceFieldType::class,
            array(
                'attr' => array(
                    'class' => 'app-price',
                    'data-price' => true,
                ),
            )
        )->add(
            'quantity',
            IntegerType::class,
            array(
                'label' => 'app.invoice.quantity',
                'required' => true,
                'attr' => array(
                    'data-quantity' => true,
                ),
            )
        )->add(
            'total',
            PriceFieldType::class,
            array(
                'label' => 'app.invoice.total',
                'mapped' => false,
                'required' => false,
                'read_only' => true,
                'attr' => array(
                    'class' => 'app-price',
                    'data-total' => true,
                ),
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\InvoiceTreatment',
            )
        );
    }

    public function getName()
    {
        return 'app_invoice_treatment';
    }

}