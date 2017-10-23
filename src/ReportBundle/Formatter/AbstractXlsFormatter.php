<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.05.2017
 * Time: 14:36
 */

namespace ReportBundle\Formatter;

use CRM\CurrencyBundle\Utils\CurrencyFormatter;
use CRM\CurrencyBundle\Utils\CurrencyManager;
use Doctrine\ORM\EntityManager;
use Oro\Bundle\EntityBundle\Provider\EntityNameResolver;
use Symfony\Component\Translation\Translator;

abstract class AbstractXlsFormatter implements XlsFormatterInterface
{
    const xlsFileName = null;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Translator */
    protected $translator;

    public function __construct(
        EntityManager $entityManager,
        Translator $translator
    )
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    public function numToXlsLetter($num)
    {
        $numeric = ($num - 1) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num - 1) / 26);
        if ($num2 > 0) {
            return $this->numToXlsLetter($num2) . $letter;
        } else {
            return $letter;
        }
    }

    public function getNextRow(\PHPExcel_Worksheet $worksheet)
    {
        return $worksheet->getHighestRow() + 1;
    }

}
