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

class RecallsXlsFormatter extends AbstractXlsFormatter implements XlsFormatterInterface
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

        //$this->renderTotals($node, $workSheet, $this->deepestLevel + 1, $workSheet->getHighestRow() + 1);

        return $this->style($workSheet);
    }

    protected function renderHeader(Node $node, \PHPExcel_Worksheet $workSheet, &$rowNum, $formData)
    {

        $colNum = $this->deepestLevel;

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
                $colNum = $this->deepestLevel;

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
        /** @var Recall $object */
        $object = $this->entityManager->getRepository('AppBundle:Recall')->find($node->getObject()->getId());

        return array(
            $this->translator->trans('app.report.recalls.status_' . $node->status),
            (string)$object->getPatient(),
            (string)$object->getPatient()->getMobilePhone(),
            (string)$object->getPatient()->getEmail(),
            $node->treatment,
        );

        return $array;
    }

    protected function getHeadersArray($formData)
    {
        $array = array(
            'app.report.recalls.status',
            'app.patient.label',
            'app.phone.label',
            'app.email',
            'app.report.recalls.last_treatment_attended',
        );

        return $array;
    }

    protected function style(\PHPExcel_Worksheet $workSheet)
    {

        foreach (range('A', $workSheet->getHighestColumn()) as $columnID) {
            $workSheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $workSheet->setSelectedCells('A1');
        return $workSheet->getParent();
    }
}
