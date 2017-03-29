<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 10:49
 */

namespace AppBundle\Form\Type;

use AppBundle\Utils\Hasher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SubscriptionFieldType extends AbstractType
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
                'class' => 'AppBundle\Entity\Subscription',
                'label' => 'app.subscription.label',
                'placeholder' => 'app.subscription.choose',
                'required' => true,
                'choice_value' => $this->hasher->choiceValueCallback(),
                'constraints' => array(
                    new NotBlank(),
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
        return 'app_subscription_selector';
    }

}
