<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 28.09.2016
 * Time: 12:09
 */

namespace AppBundle\Form\Traits;

use Symfony\Component\Form\FormInterface;

trait AddFieldOptionsTrait
{

    protected function addFieldOptions(FormInterface $form, $fieldName, array $newOptions)
    {
        $field = $form->get($fieldName);
        $config = $field->getConfig();
        $fieldTypeName = $config->getType()->getName(); // Deprecated since Symfony 3
        $fieldType = $config->getType()->getInnerType();
        $fieldOptions = $config->getOptions();

        if (array_key_exists('query_builder', $newOptions)) {
            unset(
                $fieldOptions['choices'],
                $fieldOptions['choice_list'],
                $fieldOptions['choice_loader'],
                $fieldOptions['query_builder']
            );
        }

        $form->add(
            $fieldName,
            $fieldType,
            array_merge($fieldOptions, $newOptions)
        ); //->initialize(); this triggers errors
    }

    protected function getFieldOptions(FormInterface $form, $fieldName)
    {
        $field = $form->get($fieldName);
        $config = $field->getConfig();

        return $config->getOptions();
    }

}
