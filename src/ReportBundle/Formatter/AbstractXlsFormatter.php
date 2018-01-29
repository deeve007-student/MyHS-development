<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.05.2017
 * Time: 14:36
 */

namespace ReportBundle\Formatter;

use AppBundle\Twig\FormatterExtension;
use AppBundle\Twig\PriceExtension;
use AppBundle\Twig\ReferrerExtension;
use AppBundle\Utils\Formatter;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\Translator;

abstract class AbstractXlsFormatter implements XlsFormatterInterface
{
    const xlsFileName = null;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Translator */
    protected $translator;

    /** @var  FormatterExtension */
    protected $formatterExtension;

    /** @var  PriceExtension */
    protected $priceExtension;

    /** @var  ReferrerExtension */
    protected $referrerExtension;

    /** @var  Formatter */
    protected $formatter;

    public function __construct(
        EntityManager $entityManager,
        Translator $translator,
        FormatterExtension $formatterExtension,
        PriceExtension $priceExtension,
        ReferrerExtension $referrerExtension,
        Formatter $formatter
    )
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->formatterExtension = $formatterExtension;
        $this->priceExtension = $priceExtension;
        $this->referrerExtension = $referrerExtension;
        $this->formatter = $formatter;
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