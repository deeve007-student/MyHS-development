<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:26
 */

namespace AppBundle\Form\Type;

use AppBundle\Form\Traits\ConcessionPricesTrait;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TreatmentType extends AbstractType
{

    use ConcessionPricesTrait;

    /** @var  EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addConcessionPricesField($builder, $this->entityManager);

        $builder->add(
            'name',
            TextType::class,
            array(
                'required' => false,
                'label' => 'app.treatment.label',
            )
        )->add(
            'price',
            PriceFieldType::class,
            array(
                'required' => false,
            )
        )->add(
            'code',
            TextType::class,
            array(
                'required' => false,
                'label' => 'app.treatment.code',
            )
        )->add(
            'description',
            TextareaType::class,
            array(
                'required' => false,
                'label' => 'app.treatment.description',
            )
        )->add(
            'calendarColour',
            ColorpickerType::class,
            array(
                'required' => false,
                'label' => 'app.treatment.calendar_colour',
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Treatment',
            )
        );
    }

    public function getName()
    {
        return 'app_treatment';
    }

}
