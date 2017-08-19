<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:43
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceFieldType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(
            new CallbackTransformer(
                function ($value) use ($options) {
                    if ($options['allow_blank'] && $value == 0) {
                        return '';
                    }

                    return $value;
                },
                function ($value) use ($options) {
                    $val = preg_replace('/[\s,]+/', '', $value);
                    if ($options['allow_blank'] && $val == '') {
                        return 0;
                    }

                    return $val;
                }
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'required' => true,
                'label' => 'app.product.price',
                'allow_blank' => false,
                'attr' => array(
                    'class' => 'app-price',
                ),
            )
        );
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function getName()
    {
        return 'app_price';
    }

}
