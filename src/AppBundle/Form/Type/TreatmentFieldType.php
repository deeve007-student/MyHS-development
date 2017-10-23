<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 13:21
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Treatment;
use AppBundle\Utils\Hasher;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TreatmentFieldType extends AbstractType
{

    const cssClass="app-treatment-selector select2";

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
                'class' => 'AppBundle\Entity\Treatment',
                'label' => 'app.treatment.label',
                'placeholder' => 'app.treatment.choose',
                'required' => true,
                'choice_attr' => function (Treatment $treatment, $key, $index) {
                    return [
                        'data-price' => $treatment->getPrice(),
                        'data-duration' => $treatment->getDuration(),
                    ];
                },
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('t')
                        ->orderBy('t.name', 'ASC');
                },
                'choice_value' => $this->hasher->choiceValueCallback(),
                'attr' => array(
                    'class' => self::cssClass,
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
        return 'app_treatment_selector';
    }

}
