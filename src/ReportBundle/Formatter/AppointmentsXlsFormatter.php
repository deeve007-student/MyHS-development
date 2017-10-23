<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.06.2017
 * Time: 11:18
 */

namespace ReportBundle\Formatter;

use CRM\CurrencyBundle\Twig\CurrencyExtension;
use CRM\ReportBundle\Entity\Node;
use CRM\ReportBundle\Entity\NodeValue;
use Symfony\Component\VarDumper\VarDumper;

class AppointmentsXlsFormatter extends AbstractXlsFormatter implements XlsFormatterInterface
{

    /** @var  integer */
    protected $deepestLevel;

    /**
     * @param Node $node
     * @return \PHPExcel
     */
    public function getXls($node)
    {
        $excel = new \PHPExcel();
        $workSheet = $excel->setActiveSheetIndex(0);

        $this->deepestLevel = $node->getDeepestLevel();
        $rowNum = 1;

        $this->renderHeader($node, $workSheet, $rowNum);

        $this->renderRow($node, $workSheet, $rowNum);

        $this->renderTotals($node, $workSheet, $this->deepestLevel + 1, $workSheet->getHighestRow() + 1);

        return $this->style($workSheet);
    }

    protected function renderHeader(Node $node, \PHPExcel_Worksheet $workSheet, &$rowNum)
    {

        $colNum = $this->deepestLevel + 1;

        foreach ($node->getValues() as $nodeValue) {
            if (!$nodeValue->getHidden()) {
                $values = $this->getHeadersArray();

                $firstColNum = $colNum;

                foreach ($values as $value) {
                    $workSheet->setCellValue($this->numToXlsLetter($colNum) . ($rowNum + 1), $value);
                    $colNum++;
                }

                $workSheet->setCellValue($this->numToXlsLetter($firstColNum) . $rowNum, $nodeValue->getName());
                $workSheet->mergeCells($this->numToXlsLetter($firstColNum) . $rowNum . ':' . $this->numToXlsLetter($colNum - 1) . $rowNum);
            }
        }

        $rowNum++;
    }

    protected function renderTotals(Node $node, \PHPExcel_Worksheet $workSheet, $colNum, $rowNum)
    {
        $this->renderValues($node, $workSheet, $this->deepestLevel + 1, $workSheet->getHighestRow() + 1);
        if ($this->deepestLevel > 0) {
            $workSheet->setCellValue($this->numToXlsLetter($this->deepestLevel) . $workSheet->getHighestRow(), $this->translator->trans('crm.translation.total'));
        }
    }

    protected function renderRow(Node $node, \PHPExcel_Worksheet $workSheet, &$rowNum)
    {
        if ($node->getChildren()) {
            $rowNum++;

            foreach ($node->getChildren() as $node) {
                $workSheet->setCellValue($this->numToXlsLetter($node->getLevel()) . $rowNum, $node->getName());

                $colNum = $this->deepestLevel + 1;

                $this->renderValues($node, $workSheet, $colNum, $rowNum);

                $this->renderRow($node, $workSheet, $rowNum);
            }
        }
    }

    protected function renderValues(Node $node, \PHPExcel_Worksheet $workSheet, $colNum, $rowNum)
    {
        foreach ($node->getValues() as $nodeValue) {
            if (!$nodeValue->getHidden()) {
                $values = $this->getValuesArray($nodeValue);

                foreach ($values as $value) {
                    $value = abs($value) > 0.01 ? $value : '';
                    $workSheet->setCellValue($this->numToXlsLetter($colNum) . $rowNum, $value);
                    $colNum++;
                }
            }
        }
    }

    protected function getValuesArray(NodeValue $nodeValue)
    {
        return array(
            CurrencyExtension::crmRoundAndFilter($nodeValue->getPlan()),
            CurrencyExtension::crmRoundAndFilter($nodeValue->getInv()),
            CurrencyExtension::crmRoundAndFilter($nodeValue->getFact()),
            CurrencyExtension::crmRoundAndFilter($nodeValue->getPlanM()),
            CurrencyExtension::crmRoundAndFilter($nodeValue->getFactM()),
        );
    }

    protected function getHeadersArray()
    {
        return array(
            'План',
            'Счет',
            'Факт',
            'План ГМ',
            'Факт ГМ',
        );
    }

    protected function style(\PHPExcel_Worksheet $workSheet)
    {
        foreach (range('A', $workSheet->getHighestColumn()) as $columnID) {
            $workSheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $workSheet->getStyle('A1:A' . $workSheet->getHighestRow())->getFont()->setBold(true);
        $workSheet->getStyle('A1:' . $workSheet->getHighestColumn() . '2')->getFont()->setBold(true);
        $workSheet->getStyle('A' . $workSheet->getHighestRow() . ':' . $workSheet->getHighestColumn() . $workSheet->getHighestRow())->getFont()->setBold(true);

        $workSheet->freezePane($this->numToXlsLetter($this->deepestLevel + 1) . '3');

        $workSheet->setSelectedCells('A1');
        return $workSheet->getParent();
    }
}
