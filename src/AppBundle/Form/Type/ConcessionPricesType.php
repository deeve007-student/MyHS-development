<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 31.03.2017
 * Time: 15:47
 */

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;

class ConcessionPricesType extends AbstractType
{

    /** @var  Translator */
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'label' => '',
                'required' => false,
                'entry_type' => new ConcessionPriceType($this->translator),
                'delete_empty' => false,
                'allow_add' => true,
                'allow_delete' => false,
                //'by_reference' => false,
            )
        );
    }

    public function getParent()
    {
        return CollectionType::class;
    }

    public function getName()
    {
        return 'app_concession_prices';
    }

}
