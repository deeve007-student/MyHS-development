<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.09.2017
 * Time: 17:01
 */

namespace ReportBundle\Provider;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\InvoiceProduct;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Product;
use AppBundle\Utils\DateTimeUtils;
use AppBundle\Utils\EventUtils;
use ReportBundle\Entity\ProductsPurchasedNode;
use Doctrine\ORM\QueryBuilder;
use ReportBundle\Entity\Node;
use ReportBundle\Entity\NullObject;
use ReportBundle\Form\Type\DateRangeType;
use Symfony\Component\VarDumper\VarDumper;

class ProductsPurchasedProvider extends AbstractReportProvider implements ReportProviderInterface
{

    /** @var  string */
    protected $nodeValueClass;

    /** @var  EventUtils */
    protected $eventUtils;

    public function setEventUtils(EventUtils $eventUtils)
    {
        $this->eventUtils = $eventUtils;
    }

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
        $rootNode = new ProductsPurchasedNode();

        // Получаем массив уровней вложенности отчета
        $levels = $this->getNodeLevels($reportFormData);

        // Генерируем субноды отчета опираясь на уровни вложенности отчета (к-рые могут быть динамическими)
        $this->processLevels($levels, $data, array(), $rootNode);

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

        /*
        foreach ($data as $n => $value) {
            $appointment = $this->entityManager->getRepository('AppBundle:Appointment')->find($value['appointmentId']);

            if ($appointment->getStart() < $start || $appointment->getEnd() > $end) {
                unset($data[$n]);
            } else {

                if (isset($reportFormData['firstAppointment']) && $reportFormData['firstAppointment']) {
                    $patientFirstAppointmentQb = $this->eventUtils->getActiveEventsQb(Appointment::class);
                    $patientFirstAppointmentQb->andWhere('a.patient = :patient')
                        ->setParameter('patient', $appointment->getPatient())
                        ->orderBy('a.start', 'ASC')
                        ->setMaxResults(1);

                    if ($firstAppointment = $patientFirstAppointmentQb->getQuery()->getOneOrNullResult()) {
                        if ($firstAppointment !== $appointment) {
                            unset($data[$n]);
                        }
                    }
                }

                if (isset($reportFormData['changedCancelled']) && $reportFormData['changedCancelled']) {
                    // TODO: Continue here
                }

            }
        }
        */

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
    protected function processLevels($levels, $data, array $criteria, Node $node)
    {
        $level = array_shift($levels);
        $levelData = $this->filter($data, $criteria, $level['field'], $level['class']);

        // Если последний уровень отчета - выполнятся калькуляция
        // Если нет - продолжаем рекурсию глубже по уровням
        if (count($levels) == 0) {
            $this->processObjectData($node, $levelData['objects'], $level, $data);
        } else {
            /** @var Appointment $levelObject */
            foreach ($levelData['objects'] as $levelObject) {
                $levelNode = new ProductsPurchasedNode();
                $levelNode->setObject($levelObject);

                $node->addChild($levelNode);

                if (isset($level['route']) && $level['route']) {
                    $levelNode->setRoute($this->router->generate($level['route'], array('id' => $levelObject->getId())));
                }

                $this->processLevels($levels, $levelData['data'], array($level['field'] => is_object($levelObject) ? $levelObject->getId() : $levelObject), $levelNode);
            }
        }

    }

    /**
     * Метод проходит по всем платежам ДУ, проверяет их вхождение в набор диапазонов
     * и в зависимости от этого наполняет ноду ДУ значениями
     *
     * @param Node $node
     * @param Product[] $patients
     * @param array $level
     * @throws \Exception
     */
    protected function processObjectData(Node $node, array $patients, array $level, $data)
    {
        foreach ($patients as $patient) {
            $productsPurchasedNode = new ProductsPurchasedNode();

            $productsPurchasedNode->setObject($patient);

            foreach ($data as $dataValue) {
                if (
                    $dataValue['productId'] == $node->getObject()->getId()
                    && $dataValue['patientId'] == $patient->getId()
                ) {
                    $invoiceProduct = $this->entityManager->getRepository('AppBundle:InvoiceProduct')->find($dataValue['invoiceProductId']);
                    $invoicePurchasedNode = new ProductsPurchasedNode();

                    $invoicePurchasedNode->setObject($invoiceProduct->getInvoice());
                    $invoicePurchasedNode->setRoute($this->router->generate('invoice_view', array('id' => $this->hasher->encodeObject($invoiceProduct->getInvoice()))));
                    $invoicePurchasedNode->setName($invoiceProduct->getInvoice());
                    $invoicePurchasedNode->addQuantitySold($invoiceProduct->getQuantity());
                    $invoicePurchasedNode->setDateSold($this->formatter->formatDate($invoiceProduct->getInvoice()->getDate()));
                    $invoicePurchasedNode->setCode($invoiceProduct->getProduct()->getCode());

                    $productsPurchasedNode->addChild($invoicePurchasedNode);
                }
            }

            if (isset($level['route']) && $level['route']) {
                $productsPurchasedNode->setRoute($this->router->generate($level['route'], array('id' => $this->hasher->encodeObject($patient))));
            }

            $node->addChild($productsPurchasedNode);
        }
    }

    /**
     * Возвращяет QueryBuilder для данных отчета
     *
     * @return QueryBuilder
     */
    protected function createQueryBuilder()
    {
        $qb = $this->entityManager->getRepository('AppBundle:InvoiceProduct')->createQueryBuilder('invoiceProduct')
            ->select('invoiceProduct.id AS invoiceProductId, invoice.id as invoiceId, product.id as productId, patient.id as patientId')
            ->leftJoin('invoiceProduct.product', 'product')
            ->leftJoin('invoiceProduct.invoice', 'invoice')
            ->leftJoin('invoice.patient', 'patient');

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
            $start = DateTimeUtils::getDate($reportFormData['dateStart'])->setTimezone(new \DateTimeZone('UTC'));
            $end = DateTimeUtils::getDate($reportFormData['dateEnd'])->setTime(23, 59, 59);
        } else {
            list($start, $end) = DateRangeType::getRangeDates($reportFormData['range']);
        }

        $queryBuilder->where('invoice.date >= :start')
            ->andWhere('invoice.date <= :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        if (trim($reportFormData['nameOrCode'])) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX($queryBuilder->expr()->like('lower(product.name)', ':nameOrCode'), $queryBuilder->expr()->like('lower(product.code)', ':nameOrCode'))
            )->setParameter('nameOrCode', '%' . trim(mb_strtolower($reportFormData['nameOrCode']) . '%'));
        }

        /*
        if (isset($reportFormData['treatment']) && $reportFormData['treatment']) {
            $queryBuilder->andWhere('appointment.treatment = :treatment')
                ->setParameter('treatment', $reportFormData['treatment']);
        }
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

        $productLevel = array(
            'field' => 'product',
            'class' => Product::class,
        );

        $patientLevel = array(
            'field' => 'patient',
            'class' => Patient::class,
            'route' => 'patient_view'
        );

        return array($productLevel, $patientLevel);
    }

}
