<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.03.2017
 * Time: 14:35
 */
namespace AppBundle\Form\Type\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{

    /** @var  Session */
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    protected function getFilterValues(FormInterface $form)
    {
        if ($this->session->has($form->getName())) {
            return $this->session->get($form->getName());
        }

        return null;
    }

    protected function getFilterValue(FormInterface $form, $fieldName)
    {
        if ($filterData = $this->getFilterValues($form)) {
            if (is_array($filterData) && array_key_exists($fieldName, $filterData)) {
                return $filterData[$fieldName];
            }
        }

        return null;
    }

    protected function isArrayEmpty(array $array)
    {
        foreach ($array as $key => $value) {
            if ($value && $key !== '_token') {
                return false;
            }
        }

        return true;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,

            function (FormEvent $event) {
                $data = $event->getData();
                if (is_array($data)) {

                    if (!$this->isArrayEmpty($data)) {
                        $event->getForm()->add(
                            'reset',
                            ButtonType::class,
                            array(
                                'label' => 'app.filter.reset',
                            )
                        );
                    }

                }
            }

        );

        $builder->add(
            'submit',
            SubmitType::class,
            array(
                'label' => 'app.filter.submit',
            )
        );
    }

    public function getName()
    {
        return 'app_filter';
    }
}
