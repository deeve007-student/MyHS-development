<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 31.03.2017
 * Time: 15:37
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\ConcessionPrice;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;

class ConcessionPriceType extends AbstractType
{

    /** @var  Translator */
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $entity = $event->getData();
                if ($entity instanceof ConcessionPrice) {
                    $form->add(
                        'price',
                        PriceFieldType::class,
                        array(
                            'label' => $entity->getConcession() . ' ' . $this->translator->trans('app.price'),
                            'required' => false,
                            'allow_blank' => true,
                            'attr' => array(
                                'class' => 'app-price concession-field concession-' . $entity->getConcession()->getName(),
                                'data-total' => true,
                                'placeholder' => 'app.concession.same_price',
                            ),
                        )
                    );

                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\ConcessionPrice',
            )
        );
    }

    public function getName()
    {
        return 'app_concession_price';
    }

}
