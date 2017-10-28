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

class PatientsXlsFormatter extends AbstractXlsFormatter implements XlsFormatterInterface
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

        $colNum = $this->deepestLevel + 1;

        //foreach ($node->getValues() as $nodeValue) {
        //    if (!$nodeValue->getHidden()) {
        $values = $this->getHeadersArray($formData);

        $firstColNum = $colNum;

        foreach ($values as $value) {
            $workSheet->setCellValue($this->numToXlsLetter($colNum) . ($rowNum + 1), $this->translator->trans($value));
            $colNum++;
        }

        //$workSheet->setCellValue($this->numToXlsLetter($firstColNum) . $rowNum, $nodeValue->getName());
        //$workSheet->mergeCells($this->numToXlsLetter($firstColNum) . $rowNum . ':' . $this->numToXlsLetter($colNum - 1) . $rowNum);
        //    }
        //}

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
        //foreach ($node->getValues() as $nodeValue) {
        //    if (!$nodeValue->getHidden()) {
        $values = $this->getValuesArray($node, $formData);

        foreach ($values as $value) {
            //$value = abs($value) > 0.01 ? $value : '';
            $workSheet->setCellValue($this->numToXlsLetter($colNum) . $rowNum, $value);
            $colNum++;
        }
        //    }
        //}
    }

    protected function getValuesArray(Node $node, $formData)
    {
        /** @var PatientsNode $node */

        /** @var Patient $object */
        $object = $this->entityManager->getRepository('AppBundle:Patient')->find($node->getObject()->getId());

        $array = array(
            (string)$object->getMobilePhone(),
            (string)$object->getEmail(),
            (string)$object->getReferrer(),
        );

        if ($formData['upcomingAppointment']) {
            $val = $this->formatterExtension->dateAndWeekDayFilterFull($node->getNextAppointment()->getStart());
            $val .= ' ' . $this->translator->trans('app.event.at');
            $val .= ' ' . $this->formatterExtension->timeFilter($node->getNextAppointment()->getStart());
            $val .= ' ' . $this->translator->trans('app.event.for');
            $val .= ' ' . $node->getNextAppointment()->getDurationInMinutes();
            $val .= ' ' . $this->translator->trans('app.event.minutes');

            $array[] = $val;
        }

        if ($formData['withRecall']) {
            /** @var Recall $recall */
            $vals = array();
            foreach ($node->getRecalls() as $recall) {
                $vals[] = $recall->getRecallFor() . ' (' . $recall->getRecallType() . ")";
            }
            $array[] = implode("\r\n", $vals);
        }

        if ($formData['upcomingBirthday']) {
            $array[] = $object->getDateOfBirth()->format('M d') . ', ' . $node->getAge() . ' ' . $this->translator->trans('app.report.patients.years');
        }

        return $array;

    }

    protected function getHeadersArray($formData)
    {
        $array = array(
            'app.patient.mobile_phone',
            'app.email',
            'app.patient.referrer',
        );

        if ($formData['upcomingAppointment']) {
            $array = array_merge($array, array(
                'app.appointment.label',
                'app.treatment.label',
            ));
        }

        if ($formData['withRecall']) {
            $array = array_merge($array, array(
                'app.recall.label',
            ));
        }

        if ($formData['upcomingBirthday']) {
            $array = array_merge($array, array(
                'app.report.patients.upcoming_birthday',
            ));
        }

        return $array;
    }

    protected function style(\PHPExcel_Worksheet $workSheet)
    {

        foreach (range('A', $workSheet->getHighestColumn()) as $columnID) {
            $workSheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        //$workSheet->getStyle('A1:A' . $workSheet->getHighestRow())->getFont()->setBold(true);
        //$workSheet->getStyle('A1:' . $workSheet->getHighestColumn() . '2')->getFont()->setBold(true);
        //$workSheet->getStyle('A' . $workSheet->getHighestRow() . ':' . $workSheet->getHighestColumn() . $workSheet->getHighestRow())->getFont()->setBold(true);

        $workSheet->freezePane($this->numToXlsLetter($this->deepestLevel + 1) . '2');

        $workSheet->setSelectedCells('A1');
        return $workSheet->getParent();
    }
}
