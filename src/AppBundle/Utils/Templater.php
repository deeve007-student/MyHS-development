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
        'invoiceNumber' => 'invoice.name',
        'invoiceDate' => 'invoice.date|app_date',
        'invoiceDueDate' => 'invoice.dueDateComputed|app_date',
        'patientName' => 'invoice.patient',
        'invoiceTotal' => 'invoice.total|price',
        'businessName' => 'invoice.owner.businessName',
    );

    public function __construct(\Twig_Environment $twig)
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->twig = $twig;
    }

    public function compile($template, array $objects)
    {
        $compiled = preg_replace_callback('/{{\s([^\s]*)\s}}/', function ($placeholderData) use ($objects) {

            $placeholderData[1] = $this->replaceSimplePlaceholders($placeholderData[1]);

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
