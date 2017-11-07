<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.06.2017
 * Time: 11:18
 */

namespace ReportBundle\Formatter;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Recall;
use ReportBundle\Entity\Node;
use ReportBundle\Entity\InvoicesNode;

class InvoicesXlsFormatter extends AbstractXlsFormatter implements XlsFormatterInterface
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
        /** @var InvoicesNode $node */

        /** @var Invoice $object */
        $object = $this->entityManager->getRepository('AppBundle:Invoice')->find($node->getObject()->getId());

        $array = array(
            $this->formatterExtension->dateFilter($object->getDate()),
            $this->translator->trans('app.invoice.statuses.' . $object->getStatus()),
            $object->getPaidDate() ? $this->formatterExtension->dateFilter($object->getPaidDate()) : '',
            $this->priceExtension->priceFilter($object->getTotal()),
        );

        $payments = array();
        foreach ($object->getPayments() as $payment) {
            $payments[] = $payment->getPaymentMethod() . ': ' . $this->priceExtension->priceFilter($payment->getAmount());
        }

        $array[] = implode("\r\n", $payments);

        if ($formData['unpaid']) {
            $array[] = $object->getPatient();
            $array[] = $object->getPatient()->getMobilePhone();
            $array[] = $object->getPatient()->getEmail();
            $array[] = $this->priceExtension->priceFilter($object->getAmountDue());
        }

        return $array;

    }

    protected function getHeadersArray($formData)
    {
        $array = array(
            'app.invoice.date',
            'app.invoice.status',
            'app.invoice.date_paid',
            'app.invoice.total',
            'app.invoice_payment.plural_label',
        );

        if ($formData['unpaid']) {
            $array = array_merge($array, array(
                'app.patient.label',
                'app.patient.mobile_phone',
                'app.email',
                'app.invoice.amount_due',
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
