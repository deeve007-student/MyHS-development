<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.11.17
 * Time: 21:49
 */

namespace AppBundle\Utils;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class Templater
{

    /** @var  PropertyAccessor */
    protected $accessor;

    /** @var  \Twig_Environment */
    protected $twig;

    const SIMPLE_PLACEHOLDERS = array(
        'invoiceNumber' => 'entity.name',
        'invoiceDate' => 'entity.date|app_date',
        'invoiceDueDate' => 'entity.dueDateComputed|app_date',
        'patientName' => 'entity.patient',
        'invoiceTotal' => 'entity.total|price',
        'businessName' => 'entity.owner.businessName',
        'appointmentDate' => 'entity.start|app_date_and_week_day_full',
        'appointmentTime' => 'entity.start|app_time',
        'practitionerName' => 'entity.owner',
        'treatmentType' => 'entity.treatment',
    );

    public function __construct(\Twig_Environment $twig)
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->twig = $twig;
    }

    public function compile($template, array $objects)
    {
        $compiled = preg_replace_callback('/{{\s([^\s]*)\s}}/', function ($placeholderData) use ($objects) {

            $value = null;
            $filters = [];

            if (isset($objects[$placeholderData[1]])) {
                $configData = $objects[$placeholderData[1]];
                if (is_array($configData)) {
                    $value = $configData[0];
                    if (isset($configData[1])) {
                        $filters = explode('|', $configData[1]);
                    }
                }
            } else {
                $placeholderData[1] = $this->replaceSimplePlaceholders($placeholderData[1]);
            }

            if (is_null($value)) {
                $filters = explode('|', $placeholderData[1]);
                $pathData = array_shift($filters);

                $pathParts = explode('.', $pathData);
                $objectIndex = array_shift($pathParts);
                $path = '[' . $objectIndex . '].' . implode('.', $pathParts);

                try {
                    $value = $this->accessor->getValue($objects, $path);
                } catch (\Exception $exception) {
                    return '';
                }
            }

            foreach ($filters as $filter) {
                $value = $this->applyFilter($filter, $value);
            }

            return $value;
        }, $template);

        return $compiled;
    }

    protected function applyFilter($filterName, $data)
    {
        $filter = $this->twig->getFilter($filterName);
        $filterCallable = $filter->getCallable();
        return $filterCallable($data);
    }

    protected function replaceSimplePlaceholders($placeholder)
    {
        if (isset(self::SIMPLE_PLACEHOLDERS[$placeholder])) {
            return self::SIMPLE_PLACEHOLDERS[$placeholder];
        }
        return $placeholder;
    }

}
