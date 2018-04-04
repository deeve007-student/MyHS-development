<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 12.03.18
 * Time: 14:17
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\InvoiceRefund;
use AppBundle\Entity\Refund;
use AppBundle\Entity\TreatmentPackCredit;
use AppBundle\Form\Traits\AddFieldOptionsTrait;
use AppBundle\Validator\InvoiceRefundItemSumsCorrect;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class PackRefundType extends AbstractType
{

    use AddFieldOptionsTrait;

    /** @var Translator */
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data instanceof TreatmentPackCredit) {
                $invoice = $data->getInvoiceProduct()->getInvoice();
                $maxRefundAmount = $invoice->getPossibleMaximumRefundAmount();

                $numbers = array();
                $numberRaw = \range(1, $data->getCreditsRemaining());
                foreach ($numberRaw as $amount) {
                    $numbers[$amount] = $amount;
                }
                $numbers = array_reverse($numbers, true);
                $singlePackItemPrice = $data->getProduct()->getPrice($data->getPatient()->getConcession()) / $data->getProduct()->getPackAmount();

                $form->add(
                    'treatment',
                    HiddenType::class,
                    array(
                        'required' => false,
                        'mapped' => false,
                        'data' => $data->getTreatment()->getFullName(),
                    )
                )->add(
                    'number',
                    ChoiceType::class,
                    array(
                        'required' => true,
                        'label' => 'app.treatment_pack.amount',
                        'choices' => $numbers,
                        'mapped' => false,
                        'attr' => array(
                            'class' => 'pack-refund-amount-field',
                            'style' => 'width:70px;',
                        ),
                    )
                )->add(
                    'paymentsTotal',
                    PriceFieldType::class,
                    array(
                        'label' => false,
                        'mapped' => false,
                        'data' => array_shift($numbers) * $singlePackItemPrice,
                        'read_only' => true,
                        'attr' => array(
                            'class' => 'app-price app-pack-refund-total',
                            'data-price' => $singlePackItemPrice,
                            'style' => 'width:90px;',
                        ),
                        'constraints' => array(
                            new Range(array(
                                'min' => 0,
                                'max' => $maxRefundAmount,
                                'maxMessage' => $this->translator->trans('app.refund.message.total_invalid'),
                            )),
                        )
                    )
                );
            }
        });

        $builder->add(
            'treatment',
            HiddenType::class,
            array(
                'required' => false,
                'mapped' => false,
            )
        )->add(
            'number',
            ChoiceType::class,
            array(
                'required' => true,
                'label' => 'app.treatment_pack.amount',
                'choices' => array(),
                'mapped' => false,
                'attr' => array(
                    'class' => 'pack-refund-amount-field',
                    'style' => 'width:70px;',
                ),
            )
        )->add(
            'paymentsTotal',
            PriceFieldType::class,
            array(
                'label' => false,
                'mapped' => false,
                'data' => 0,
                'read_only' => true,
                'attr' => array(
                    'class' => 'app-price app-pack-refund-total',
                    'style' => 'width:90px;',
                )
            )
        )->add(
            'paymentMethod', EntityType::class,
            array(
                'required' => false,
                'mapped' => false,
                'class' => 'AppBundle\Entity\InvoicePaymentMethod',
                'label' => 'app.refund.payment_method',
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('pm')
                        ->where('pm.name != :hicaps')
                        ->setParameter('hicaps', 'Hicaps');
                },
                'constraints' => array(new NotBlank()),
            )
        );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\TreatmentPackCredit',
            )
        );
    }

    public function getName()
    {
        return 'app_pack_refund';
    }

}
