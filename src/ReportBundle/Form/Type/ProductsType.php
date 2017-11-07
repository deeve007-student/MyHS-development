<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.05.2017
 * Time: 13:16
 */

namespace ReportBundle\Form\Type;

use AppBundle\Entity\Product;
use AppBundle\Form\Type\DateType;
use AppBundle\Form\Type\TreatmentFieldType;
use AppBundle\Form\Type\TreatmentType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsType extends AbstractReportType
{

    /** @var  EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $suppliers = array();
        $suppliersList = array();
        /** @var Product $product */
        foreach ($this->entityManager->getRepository('AppBundle:Product')->findAll() as $product) {
            if ($supplier = trim($product->getSupplier())) {
                $suppliers[] = $supplier;
            }
        }
        $suppliers = array_unique($suppliers);
        asort($suppliers);
        foreach ($suppliers as $supplier) {
            $suppliersList[$supplier] = $supplier;
        }

        $builder->add(
            'nameOrCode',
            TextType::class,
            [
                'required' => false,
                'label' => 'app.report.products.name_or_code',
            ]
        )->add(
            'supplier',
            ChoiceType::class,
            [
                'placeholder' => 'app.report.products.choose_supplier',
                'required' => false,
                'label' => 'app.product.supplier',
                'choices' => $suppliersList,
            ]
        )->add(
            'stockLevel',
            IntegerType::class,
            [
                'required' => false,
                'label' => 'app.product.stock_level',
            ]
        )->add(
            'range',
            DateRangeType::class,
            [
                'ranges' => array(
                    DateRangeType::CHOICE_ALL,
                    DateRangeType::CHOICE_MONTH,
                    DateRangeType::CHOICE_PREV_MONTH,
                    DateRangeType::CHOICE_QUARTER,
                    DateRangeType::CHOICE_PREV_QUARTER,
                    DateRangeType::CHOICE_NEXT_QUARTER,
                    DateRangeType::CHOICE_YEAR,
                    DateRangeType::RANGE,
                ),
                'required' => true,
                'label' => 'app.report.products.when_item_sold',
            ]
        )->add(
            'dateStart',
            DateType::class,
            [
                'required' => false,
            ]
        )->add(
            'dateEnd',
            DateType::class,
            [
                'required' => false,
            ]
        );

        parent::buildForm($builder, $options);

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'xls' => true,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_report_products';
    }
}
