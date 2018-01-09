<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.06.2017
 * Time: 11:18
 */

namespace ReportBundle\Formatter;

use AppBundle\Entity\Patient;
use AppBundle\Entity\Recall;
use ReportBundle\Entity\Node;
use ReportBundle\Entity\PatientsNode;

class PatientRetentionXlsFormatter extends AbstractXlsFormatter implements XlsFormatterInterface
{

    /** @var  integer */
    protected $deepestLevel;

    /**
     * @param Node $node
     * @return \PHPExcel
     */
    public function getXls($node, $formData)
    {
        $excel = new \PHPExcel();
        $workSheet = $excel->setActiveSheetIndex(0);

        $this->deepestLevel = $node->getDeepestLevel();
        $rowNum = 0;

        $this->renderHeader($node, $workSheet, $rowNum, $formData);

        $this->renderRow($node, $workSheet, $rowNum, $formData);

        return $this->style($workSheet);
    }

    protected function renderHeader(Node $node, \PHPExcel_Worksheet $workSheet, &$rowNum, $formData)
    {

        $colNum = $this->deepestLevel + 1;

        $values = $this->getHeadersArray($formData);

        foreach ($values as $value) {
            $workSheet->setCellValue($this->numToXlsLetter($colNum) . ($rowNum + 1), $this->translator->trans($value));
            $colNum++;
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

    protected function renderRow(Node $node, \PHPExcel_Worksheet $workSheet, &$rowNum, $formData)
    {
        if ($node->getChildren()) {
            $rowNum++;

            foreach ($node->getChildren() as $node) {
                $workSheet->setCellValue($this->numToXlsLetter($node->getLevel()) . $rowNum, $node->getName());

                $colNum = $this->deepestLevel + 1;

                $this->renderValues($node, $workSheet, $colNum, $rowNum, $formData);

                $this->renderRow($node, $workSheet, $rowNum, $formData);
            }
        }
    }

    protected function renderValues(Node $node, \PHPExcel_Worksheet $workSheet, $colNum, $rowNum, $formData)
    {
        $values = $this->getValuesArray($node, $formData);

        foreach ($values as $value) {
            $workSheet->setCellValue($this->numToXlsLetter($colNum) . $rowNum, $value);
            $colNum++;
        }
    }

    protected function getValuesArray(Node $node, $formData)
    {
        $array = array(
            (string)$node->getNew(),
            (string)$node->getAtt5p(),
            (string)$node->getAtt10p(),
            (string)$node->getAtt20p(),
            (string)$node->getAtt50p(),
        );

        return $array;

    }

    protected function getHeadersArray($formData)
    {
        $array = array(
            'app.report.patient_retention.new',
            'app.report.patient_retention.att5',
            'app.report.patient_retention.att10',
            'app.report.patient_retention.att20',
            'app.report.patient_retention.att50',
        );

        return $array;
    }

    protected function style(\PHPExcel_Worksheet $workSheet)
    {

        foreach (range('A', $workSheet->getHighestColumn()) as $columnID) {
            $workSheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        
        $workSheet->freezePane($this->numToXlsLetter($this->deepestLevel + 1) . '2');

        $workSheet->setSelectedCells('A1');
        return $workSheet->getParent();
    }
}
