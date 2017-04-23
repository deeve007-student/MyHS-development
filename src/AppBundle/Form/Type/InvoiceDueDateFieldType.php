<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 13:52
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceDueDateFieldType extends AbstractType
{

    protected $allowedValues = array(
        '15' => '15 days',
        '30' => '30 days',
        '45' => '45 days',
        '60' => '60 days',
        '90' => '90 days',
        'Custom' => 'Custom',
    );

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'value',
            ChoiceType::class,
            array(
                'placeholder' => 'app.invoice.due_date_choose',
                'label' => 'app.invoice.due_date',
                'required' => true,
                'choices' => $this->allowedValues,
            )
        )->add(
            'customValue',
            IntegerType::class,
            array(
                'label' => 'app.invoice.custom_due_date',
                'required' => false,
                'attr' => array(
                    'placeholder'=>'app.invoice.custom_due_date',
                ),
            )
        );

        $builder->addModelTransformer(
            new CallbackTransformer(
                function ($value) {

                    if (!$value) {
                        return array(
                            'value' => null,
                            'customValue' => null,
                        );
                    }

                    if (in_array($value, $this->allowedValues)) {
                        return array(
                            'value' => $value,
                            'customValue' => null,
                        );
                    }

                    return array(
                        'value' => 'Custom',
                        'customValue' => $value,
                    );
                },
                function ($values) {
                    return ($values['customValue'] ? $values['customValue'] : $values['value']);
                }
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'compound' => true,
                'error_bubbling' => false,
                'label' => 'app.invoice.due_date',
            )
        );
    }

    public function getName()
    {
        return 'app_invoice_due_date_selector';
    }

}
