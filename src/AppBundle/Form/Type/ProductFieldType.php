<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 13:21
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Product;
use AppBundle\Utils\Hasher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFieldType extends AbstractType
{

    /** @var  Hasher */
    protected $hasher;

    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'class' => 'AppBundle\Entity\Product',
                'label' => 'app.product.label',
                'placeholder' => 'app.product.choose',
                'required' => true,
                'choice_attr' => function (Product $product, $key, $index) {
                    return [
                        'data-price' => $product->getPrice(),
                    ];
                },
                'choice_value' => $this->hasher->choiceValueCallback(),
                'attr' => array(
                    'class' => 'app-product-selector select2',
                ),
            )
        );
    }

    public function getParent()
    {
        return EntityType::class;
    }

    public function getName()
    {
        return 'app_product_selector';
    }

}
