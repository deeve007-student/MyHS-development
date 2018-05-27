<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:26
 */

namespace AppBundle\Form\Type;

use AppBundle\Form\Traits\ConcessionPricesTrait;
use AppBundle\Utils\EventUtils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;
use Symfony\Component\VarDumper\VarDumper;

class TreatmentType extends AbstractType
{

    use ConcessionPricesTrait;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  EventUtils */
    protected $eventUtils;

    /** @var  Translator */
    protected $translator;

    public function __construct(EntityManager $entityManager, EventUtils $eventUtils, Translator $translator)
    {
        $this->entityManager = $entityManager;
        $this->eventUtils = $eventUtils;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addConcessionPricesField($builder, $this->entityManager);

        // If modality selected - clear all unused fields on submit
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();

            if ($data['parent'] == true) {
                $data['code'] = "";
                $data['parentTreatment'] = "";
                $data['price'] = "0";
                $data['calendarColour'] = "";
                foreach ($data['concessionPrices'] as $n => $concessionPrice) {
                    $data['concessionPrices'][$n]['price'] = 0;
                }
                $event->setData($data);
            }

        });

        $interval = $this->eventUtils->getInterval();
        $durations = array();
        for ($d = $interval; $d <= 240; $d = $d + $interval) {
            $durations[$d] = $d . ' ' . $this->translator->trans('app.event.minute_short');
        }

        $builder->add(
            'parent',
            ChoiceType::class,
            array(
                'required' => true,
                'label' => 'app.treatment.type',
                'choices' => array(
                    '0' => 'app.treatment.types.treatment',
                    '1' => 'app.treatment.types.treatment_modality',
                ),
                'attr' => array(
                    'class' => 'treatment-type-selector',
                )
            )
        )->add(
            'parentTreatment',
            EntityType::class,
            array(
                'required' => false,
                'class' => 'AppBundle\Entity\Treatment',
                'label' => 'app.treatment.parent',
                'placeholder' => 'app.treatment.parent_placeholder',
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('t')
                        ->where('t.parent = :true')
                        ->setParameter('true', true)
                        ->orderBy('t.name', 'ASC');
                },
                'attr' => array(
                    'class' => 'treatment-parent-selector',
                )
            )
        )->add(
            'name',
            TextType::class,
            array(
                'required' => false,
                'label' => 'app.treatment.name',
                'attr' => array(
                    'class' => 'treatment-name-field',
                )
            )
        )->add(
            'price',
            PriceFieldType::class,
            array(
                'required' => false,
                'attr' => array(
                    'class' => 'treatment-price-field',
                )
            )
        )->add(
            'duration',
            ChoiceType::class,
            array(
                'required' => false,
                'label' => 'app.treatment.duration',
                'placeholder' => 'app.treatment.choose_duration',
                'choices' => $durations,
                'attr' => array(
                    'class' => 'treatment-duration-field',
                )
            )
        )->add(
            'code',
            TextType::class,
            array(
                'required' => false,
                'label' => 'app.treatment.code',
                'attr' => array(
                    'class' => 'treatment-code-field',
                )
            )
        )->add(
            'description',
            TextareaType::class,
            array(
                'required' => false,
                'label' => 'app.treatment.description',
                'attr' => array(
                    'class' => 'treatment-description-field',
                )
            )
        )->add(
            'calendarColour',
            ColorpickerType::class,
            array(
                'required' => false,
                'label' => 'app.treatment.calendar_colour',
                'attr' => array(
                    'class' => 'treatment-colour-field',
                )
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
