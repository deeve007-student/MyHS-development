<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 03.03.2017
 * Time: 20:26
 */

namespace AppBundle\Form\Type;

use AppBundle\Utils\Hasher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountryFieldType extends AbstractType
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
                'class' => 'AppBundle\Entity\Country',
                'label' => 'app.country.label',
                'placeholder' => 'app.country.choose',
                'required' => true,
                'choice_value' => $this->hasher->choiceValueCallback(),
            )
        );
    }

    public function getParent()
    {
        return EntityType::class;
    }

    public function getName()
    {
        return 'app_country_selector';
    }

}