<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.06.2017
 * Time: 11:18
 */

namespace ReportBundle\Formatter;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\AppointmentPatient;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Recall;
use ReportBundle\Entity\Node;
use ReportBundle\Entity\AppointmentsNode;

class AppointmentsXlsFormatter extends AbstractXlsFormatter implements XlsFormatterInterface
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
        /** @var AppointmentsNode $node */

        /** @var AppointmentPatient $object */
        $object = $this->entityManager->getRepository('AppBundle:AppointmentPatient')->find($node->getObject()->getId());

        $array = array(
            $object->getPatient()->getMobilePhone(),
            $object->getPatient()->getEmail(),
            $this->formatter->formatDate($object->getAppointment()->getStart()) . ', ' . $this->formatterExtension->timeFilter($object->getAppointment()->getStart()),
            $object->getAppointment()->getTreatment(),
        );

        if ($formData['changedCancelled']) {
            $array[] = $node->getType();
            if ($node->getOriginalStart()) {
                $array[] = $this->formatter->formatDate($node->getOriginalStart()) . ', ' . $this->formatterExtension->timeFilter($node->getOriginalStart());
            } else {
                $array[] = '';
            }
            $array[] = $node->getReason();
        }

        if ($formData['firstAppointment']) {
            $array[] = $object->getPatient()->getReferrer();
        }

        return $array;

    }

    protected function getHeadersArray($formData)
    {
        $array = array(
            'app.patient.mobile_phone',
            'app.email',
            'app.report.appointments.start',
            'app.treatment.label',
        );

        if ($formData['firstAppointment']) {
            $array = array_merge($array, array(
                'app.patient.referrer',
            ));
        }

        if ($formData['changedCancelled']) {
            $array = array_merge($array, array(
                'app.report.appointments.type',
                'app.report.appointments.original_appointment',
                'app.report.appointments.reason',
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
