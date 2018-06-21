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
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TreatmentFieldType
 */
class TreatmentFieldType extends AbstractType
{

    const CSS_CLASS = "app-treatment-selector select2";

    /** @var  Hasher */
    protected $hasher;

    /**
     * TreatmentFieldType constructor.
     * @param Hasher $hasher
     */
    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'show_no_fee' => false,
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
                        ->where('t.parent = :false')
                        ->setParameter('false', false)
                        ->orderBy('t.name', 'ASC');
                },
                'choice_value' => $this->hasher->choiceValueCallback(),
                'attr' => [
                    'class' => self::CSS_CLASS,
                ],
            ]
        );

        $resolver->setDefault('query_builder', function (Options $options) {
            return function (EntityRepository $repo) use ($options) {
                $qb = $repo->createQueryBuilder('t')
                    ->where('t.parent = :false')
                    ->setParameter('false', false)
                    ->orderBy('t.name', 'ASC');

                if (false === $options['show_no_fee']) {
                    $qb->andWhere('t.noShowFee = :false')
                        ->setParameter('false', false);
                }

                return $qb;
            };
        });
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return EntityType::class;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_treatment_selector';
    }

}
