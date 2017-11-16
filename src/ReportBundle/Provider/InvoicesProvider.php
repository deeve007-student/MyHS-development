<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.09.2017
 * Time: 17:01
 */

namespace ReportBundle\Provider;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoicePayment;
use AppBundle\Entity\Patient;
use AppBundle\Utils\DateTimeUtils;
use AppBundle\Utils\EventUtils;
use ReportBundle\Entity\AppointmentsNode;
use Doctrine\ORM\QueryBuilder;
use ReportBundle\Entity\Node;
use ReportBundle\Entity\NullObject;
use ReportBundle\Entity\InvoicesNode;
use ReportBundle\Form\Type\DateRangeType;
use Symfony\Component\VarDumper\VarDumper;

class InvoicesProvider extends AbstractReportProvider implements ReportProviderInterface
{

    /** @var  string */
    protected $nodeValueClass;

    /**
     * @param $reportFormData
     * @return Node
     */
    public function getReportData($reportFormData)
    {
        // Получаем из БД массив данных для отчета, дважды фильтруя (второй раз - по вычисляемым значениям)
        $qb = $this->createQueryBuilder();
        $this->bindReportFormToQueryBuilder($qb, $reportFormData); // Первая фильтрация - на уровне запроса
        $data = $qb->getQuery()->getResult();
        $this->filterResults($data, $reportFormData); // Вторая фильтрация - по вычисляемым значениям

        // Создаем главную ноду отчета. Значения в ней нужны для автоподстчета итогов
        $rootNode = new AppointmentsNode();

        // Получаем массив уровней вложенности отчета
        $levels = $this->getNodeLevels($reportFormData);

        // Генерируем субноды отчета опираясь на уровни вложенности отчета (к-рые могут быть динамическими)
        $this->processLevels($levels, $data, array(), $rootNode, $reportFormData);

        return $rootNode;
    }

    /**
     * Фильтрует результат выборки по параметрам формы фильтров и вычисляемым значениям
     * (тем, что нельзя использовать на стадии выборки из БД)
     *
     *
     * @param $data
     * @param $reportFormData
     */
    protected function filterResults(&$data, $reportFormData)
    {


        if ($reportFormData['range'] == 'range') {
            $dueDateStart = DateTimeUtils::getDate($reportFormData['dateStart'])->setTimezone(new \DateTimeZone('UTC'));
            $dueDateEnd = DateTimeUtils::getDate($reportFormData['dateEnd'])->setTime(23, 59, 59);
        } else {
            list($dueDateStart, $dueDateEnd) = DateRangeType::getRangeDates($reportFormData['range']);
        }

        if ($reportFormData['paidRange']) {
            if ($reportFormData['paidRange'] == 'range') {
                $paidStart = DateTimeUtils::getDate($reportFormData['paidStart'])->setTimezone(new \DateTimeZone('UTC'));
                $paidEnd = DateTimeUtils::getDate($reportFormData['paidEnd'])->setTime(23, 59, 59);
            } else {
                list($paidStart, $paidEnd) = DateRangeType::getRangeDates($reportFormData['paidRange']);
            }
        }

        if ($reportFormData['unpaidRange'] == 'range') {
            $unpaidStart = DateTimeUtils::getDate($reportFormData['unpaidStart'])->setTimezone(new \DateTimeZone('UTC'));
            $unpaidEnd = DateTimeUtils::getDate($reportFormData['unpaidEnd'])->setTime(23, 59, 59);
        } else {
            list($unpaidStart, $unpaidEnd) = DateRangeType::getRangeDates($reportFormData['unpaidRange']);
        }

        foreach ($data as $n => $value) {
            /** @var Invoice $invoice */
            $invoice = $this->entityManager->getRepository('AppBundle:Invoice')->find($value['invoiceId']);

            if ($reportFormData['paidRange']) {
                if (!$invoice->getPaidDate() || ($invoice->getPaidDate() && ($invoice->getPaidDate() <= $paidStart || $invoice->getPaidDate() >= $paidEnd))) {
                    unset($data[$n]);
                }
            }

            if ($reportFormData['productsOnly']) {
                if ($invoice->getInvoiceTreatments()->count() || !$invoice->getInvoiceProducts()->count()) {
                    unset($data[$n]);
                }
            }

            if ($reportFormData['unpaid']) {
                if ($invoice->getStatus() == Invoice::STATUS_PAID) {
                    unset($data[$n]);
                }
            }

            if ($invoice->getDueDateComputed() <= $dueDateStart || $invoice->getDueDateComputed() >= $dueDateEnd) {
                unset($data[$n]);
            }
        }

    }

    /**
     * Фильтрует массив с данными по переданным критериям, уменьшая его каждый раз
     * Дополнительно возвращает массив различных объектов из этих данных по указанному полю
     *
     * @param array $data
     * @param array $criteria Критерии отбора значений из массива
     * @param string $field Имя поля с Id объектов в массиве с данными
     * @param string $class Класс объектов, к-рый ищем в поле (параметр $field)
     * @return array
     */
    protected function filter(array $data, array $criteria, $field, $class)
    {
        foreach ($criteria as $criteriaField => $criteriaValue) {
            $data = array_filter($data, function ($row) use ($criteriaField, $criteriaValue) {
                return $row[$criteriaField . 'Id'] == $criteriaValue ? true : false;
            });
        }

        $objects = array_map(function ($id) use ($class) {
            if ($id) {
                $object = $this->entityManager->getRepository($class)->findOneBy(array('id' => $id));
            } else {
                $object = new NullObject();
            }
            return $object;
        }, array_values(array_unique(array_map(function ($row) use ($field) {
            return $row[$field . 'Id'];
        }, $data))));

        $this->sortObjects($objects);

        return array(
            'data' => $data,
            'objects' => $objects,
        );
    }

    /**
     * Проходит по уровням отчета и фильтрует данные для них
     * Для последнего уровня - рассчитывает значения
     *
     * @param $levels
     * @param $data
     * @param array $criteria
     * @param Node $node
     */
    protected function processLevels($levels, $data, array $criteria, Node $node, $reportFormData)
    {
        $level = array_shift($levels);
        $levelData = $this->filter($data, $criteria, $level['field'], $level['class']);

        // Если последний уровень отчета - выполнятся калькуляция
        // Если нет - продолжаем рекурсию глубже по уровням
        if (count($levels) == 0) {
            $this->processObjectData($node, $levelData['objects'], $level, $reportFormData);
        } else {
            /** @var Appointment $levelObject */
            foreach ($levelData['objects'] as $levelObject) {
                $levelNode = new AppointmentsNode();
                $levelNode->setObject($levelObject);

                $node->addChild($levelNode);

                if (isset($level['route']) && $level['route']) {
                    $levelNode->setRoute($this->router->generate($level['route'], array('id' => $levelObject->getId())));
                }

                $this->processLevels($levels, $levelData['data'], array($level['field'] => is_object($levelObject) ? $levelObject->getId() : $levelObject), $levelNode, $reportFormData);
            }
        }

    }

    /**
     * Метод проходит по всем платежам ДУ, проверяет их вхождение в набор диапазонов
     * и в зависимости от этого наполняет ноду ДУ значениями
     *
     * @param Node $node
     * @param Invoice[] $invoices
     * @param array $level
     * @throws \Exception
     */
    protected function processObjectData(Node $node, array $invoices, array $level, $reportFormData)
    {

        /** @var Invoice $invoice */
        foreach ($invoices as $invoice) {
            $invoiceNode = new InvoicesNode();

            $invoiceNode->setObject($invoice);

            $payments = array();
            /** @var InvoicePayment $payment */
            foreach ($invoice->getPayments() as $payment) {
                if (!isset($payments[$payment->getPaymentMethod()->getName()])) {
                    $payments[$payment->getPaymentMethod()->getName()] = $payment->getAmount();
                } else {
                    $payments[$payment->getPaymentMethod()->getName()] += $payment->getAmount();
                }
            }
            $invoiceNode->setPayments($payments);

            if (isset($level['route']) && $level['route']) {
                $invoiceNode->setRoute($this->router->generate($level['route'], array('id' => $this->hasher->encodeObject($invoice))));
            }

            $node->addChild($invoiceNode);
        }
    }

    /**
     * Возвращяет QueryBuilder для данных отчета
     *
     * @return QueryBuilder
     */
    protected function createQueryBuilder()
    {
        $qb = $this->entityManager->getRepository('AppBundle:Invoice')->createQueryBuilder('invoice')
            ->select('invoice.id AS invoiceId');

        return $qb;
    }

    /**
     * Применяет значения из филтров отчета к QueryBuilder
     * Применяются лишь те фильтры, чьи значения смаплены в БД.
     * Вычисляемые значения фильтруются в другом методе
     *
     * @param QueryBuilder $queryBuilder
     * @param array $reportFormData
     */
    protected function bindReportFormToQueryBuilder(QueryBuilder $queryBuilder, array $reportFormData)
    {

        if ($reportFormData['range'] == 'range') {
            $dueDateStart = DateTimeUtils::getDate($reportFormData['dateStart'])->setTimezone(new \DateTimeZone('UTC'));
            $dueDateEnd = DateTimeUtils::getDate($reportFormData['dateEnd'])->setTime(23, 59, 59);
        } else {
            list($dueDateStart, $dueDateEnd) = DateRangeType::getRangeDates($reportFormData['range']);
        }

        if ($reportFormData['paidRange'] == 'range') {
            $paidStart = DateTimeUtils::getDate($reportFormData['paidStart'])->setTimezone(new \DateTimeZone('UTC'));
            $paidEnd = DateTimeUtils::getDate($reportFormData['paidEnd'])->setTime(23, 59, 59);
        } else {
            list($paidStart, $paidEnd) = DateRangeType::getRangeDates($reportFormData['paidRange']);
        }

        if ($reportFormData['unpaidRange'] == 'range') {
            $unpaidStart = DateTimeUtils::getDate($reportFormData['unpaidStart'])->setTimezone(new \DateTimeZone('UTC'));
            $unpaidEnd = DateTimeUtils::getDate($reportFormData['unpaidEnd'])->setTime(23, 59, 59);
        } else {
            list($unpaidStart, $unpaidEnd) = DateRangeType::getRangeDates($reportFormData['unpaidRange']);
        }

        /*
            $queryBuilder->andWhere('invoice.paidDate >= :paidStart')
                ->andWhere('invoice.paidDate <= :paidEnd')
                ->setParameter('paidStart', $paidStart)
                ->setParameter('paidEnd', $paidEnd);
        */
    }

    /**
     * Возвращает массив уровней вложенности отчета
     * Для каждого из них могут быть указаны роут, класс, ACL ресурс (нужно для генерации ссылок на объекты)
     *
     * @param $reportFormData
     * @return array
     * @throws \Exception
     */
    protected function getNodeLevels($reportFormData)
    {

        $invoiceLevel = array(
            'field' => 'invoice',
            'class' => Invoice::class,
            'route' => 'invoice_view',
        );

        /*
        $buLevel = array(
            'field' => 'businessUnit',
            'class' => BusinessUnit::class,
        );

        $erLevel = array(
            'field' => 'expenseRequest',
            'class' => ExpenseRequest::class,
            'route' => 'crm_expense_request_view',
            'acl' => 'crm_expense_request_view',
        );

        switch ($reportFormData['group']) {
            case 'businessUnit':
                return array($buLevel, $erLevel);
                break;
            case 'expenditure':
                return array($expenditureLevel, $erLevel);
                break;
        }
        */

        return array($invoiceLevel);

        //throw new \Exception('Undefined grouping: ' . $reportFormData['group']);
    }

}