<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:26
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            TextType::class,
            array(
                'required' => true,
                'label' => 'app.product.name',
            )
        )->add(
            'price',
            PriceFieldType::class
        )->add(
            'code',
            TextType::class,
            array(
                'required' => false,
                'label' => 'app.product.code',
            )
        )->add(
            'supplier',
            TextType::class,
            array(
                'required' => false,
                'label' => 'app.product.supplier',
            )
        )->add(
            'costPrice',
            PriceFieldType::class,
            array(
                'required' => false,
                'label' => 'app.product.cost_price',
                'allow_blank' => true,
            )
        )->add(
            'stockLevel',
            IntegerType::class,
            array(
                'required' => false,
                'label' => 'app.product.stock_level',
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Product',
            )
        );
    }

    public function getName()
    {
        return 'app_product';
    }

}
