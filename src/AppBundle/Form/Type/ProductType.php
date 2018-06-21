<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:26
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Product;
use AppBundle\Form\Traits\AddFieldOptionsTrait;
use AppBundle\Form\Traits\ConcessionPricesTrait;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\VarDumper\VarDumper;

class ProductType extends AbstractType
{

    use ConcessionPricesTrait;
    use AddFieldOptionsTrait;

    /** @var  EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addConcessionPricesField($builder, $this->entityManager);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data instanceof Product) {
                if ($data->getId() && $data->isPack()) {
                    $this->addFieldOptions($form, 'type', array(
                        'data' => 'pack'
                    ));
                }
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data['type'] == 'pack') {
                $this->addFieldOptions($form, 'treatment', array(
                    'constraints' => array(
                        new NotBlank(),
                    )
                ));
                $this->addFieldOptions($form, 'packAmount', array(
                    'constraints' => array(
                        new NotBlank(),
                    )
                ));
                $this->addFieldOptions($form, 'singleTreatmentPrice', array(
                    'constraints' => array(
                        new NotBlank(),
                    )
                ));
                $this->addFieldOptions($form, 'singleTreatmentPrice', array(
                    'constraints' => array(
                        new NotBlank(),
                        new Range(array('min' => 0)),
                    )
                ));
            }

            if ($data['type'] == 'standard') {
                $this->addFieldOptions($form, 'name', array(
                    'constraints' => array(
                        new NotBlank(),
                    )
                ));
                $this->addFieldOptions($form, 'price', array(
                    'constraints' => array(
                        new NotBlank(),
                        new Range(array('min' => 0)),
                    )
                ));
            }

        });

        $packAmounts = array();
        $packAmountsRaw = \range(1, 20);
        foreach ($packAmountsRaw as $amount) {
            $packAmounts[$amount] = $amount;
        }

        $builder->add(
            'type',
            ChoiceType::class,
            array(
                'required' => true,
                'mapped' => false,
                'label' => 'app.product.product_type_label',
                'choices' => array(
                    'standard' => 'app.product.type',
                    'pack' => 'app.treatment_pack.type',
                ),
                'attr' => array(
                    'class' => 'product-type-selector',
                )
            )
        )->add(
            'name',
            TextType::class,
            array(
                'required' => false,
                'label' => 'app.product.name',
                'attr' => array(
                    'class' => 'name-field',
                )
            )
        )->add(
            'price',
            PriceFieldType::class,
            array(
                'required' => false,
                'attr' => array(
                    'class' => 'app-price price-field',
                ),
            )
        )->add(
            'singleTreatmentPrice',
            PriceFieldType::class,
            array(
                'required' => false,
                'label' => 'app.treatment_pack.single_price',
                'attr' => array(
                    'class' => 'app-price single-treatment-price-field',
                ),
            )
        )->add(
            'code',
            TextType::class,
            array(
                'required' => false,
                'label' => 'app.product.code',
                'attr' => array(
                    'class' => 'code-field',
                )
            )
        )->add(
            'supplier',
            TextType::class,
            array(
                'required' => false,
                'label' => 'app.product.supplier',
                'attr' => array(
                    'class' => 'supplier-field',
                ),
            )
        )->add(
            'costPrice',
            PriceFieldType::class,
            array(
                'required' => false,
                'label' => 'app.product.cost_price',
                'allow_blank' => true,
                'attr' => array(
                    'class' => 'app-price cost-price-field',
                ),
            )
        )->add(
            'stockLevel',
            IntegerType::class,
            array(
                'required' => true,
                'label' => 'app.product.stock_level',
                'attr' => array(
                    'class' => 'stock-level-field',
                ),
            )
        )->add(
            'packAmount',
            ChoiceType::class,
            array(
                'required' => false,
                'label' => 'app.treatment_pack.amount',
                'choices' => $packAmounts,
                'attr' => array(
                    'class' => 'pack-amount-field',
                ),
            )
        )->add(
            'treatment',
            TreatmentFieldType::class,
            array(
                'attr' => array(
                    'class' => TreatmentFieldType::CSS_CLASS . ' treatment-field',
                ),
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
