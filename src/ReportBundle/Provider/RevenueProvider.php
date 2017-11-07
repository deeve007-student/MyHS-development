<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.09.2017
 * Time: 17:01
 */

namespace ReportBundle\Provider;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Patient;
use AppBundle\Utils\DateRangeUtils;
use AppBundle\Utils\DateTimeUtils;
use AppBundle\Utils\EventUtils;
use ReportBundle\Entity\RevenueNode;
use Doctrine\ORM\QueryBuilder;
use ReportBundle\Entity\Node;
use ReportBundle\Entity\NullObject;
use ReportBundle\Entity\PatientsNode;
use ReportBundle\Form\Type\DateRangeType;
use Symfony\Component\VarDumper\VarDumper;

class RevenueProvider extends AbstractReportProvider implements ReportProviderInterface
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
        //$qb = $this->createQueryBuilder();
        //$this->bindReportFormToQueryBuilder($qb, $reportFormData); // Первая фильтрация - на уровне запроса
        //$data = $qb->getQuery()->getResult();
        //$this->filterResults($data, $reportFormData); // Вторая фильтрация - по вычисляемым значениям

        // Создаем главную ноду отчета. Значения в ней нужны для автоподстчета итогов
        $rootNode = new RevenueNode();

        if ($reportFormData['range'] == 'range') {
            $dateStart = DateTimeUtils::getDate($reportFormData['dateStart'])->setTimezone(new \DateTimeZone('UTC'));
            $dateEnd = DateTimeUtils::getDate($reportFormData['dateEnd'])->setTime(23, 59, 59);
        } else {
            list($dateStart, $dateEnd) = DateRangeType::getRangeDates($reportFormData['range']);
        }

        if ($reportFormData['range'] !== 'today') {
            $monthes = DateRangeUtils::getMonthesArrayBetweenTwoDates($dateStart, $dateEnd);

            foreach ($monthes as $month) {
                $monthNode = new RevenueNode();
                $this->calculateData($monthNode, $month['start'], $month['end']);
                $rootNode->addChild($monthNode);
            }
        } else {
            $todayNode = new RevenueNode();
            $this->calculateData($todayNode, $dateStart, $dateEnd, true);
            $rootNode->addChild($todayNode);
        }

        return $rootNode;
    }

    protected function calculateData(RevenueNode $node, $dateStart, $dateEnd, $onlyToday = false)
    {
        $invoices = $this->entityManager->getRepository('AppBundle:Invoice')->findAll();

        $node->setName($dateStart->format('F Y'));

        if ($onlyToday) {
            $node->setName($this->formatter->formatDate($dateStart));
        }

        foreach ($invoices as $invoice) {
            if ($invoice->getDate() >= $dateStart && $invoice->getDate() <= $dateEnd) {

                if (!$node->getClients()->contains($invoice->getPatient())) {
                    $node->addClient($invoice->getPatient());
                }

                foreach ($invoice->getInvoiceProducts() as $product) {
                    $node->setProductsBilled($node->getProductsBilled() + $product->getTotal());
                }
                foreach ($invoice->getInvoiceTreatments() as $treatment) {
                    $node->setServicesBilled($node->getServicesBilled() + $treatment->getTotal());
                }
            }
            if ($invoice->getPaidDate() >= $dateStart && $invoice->getPaidDate() <= $dateEnd) {
                foreach ($invoice->getInvoiceProducts() as $product) {
                    $node->setProductsPaid($node->getProductsPaid() + $product->getTotal());
                    $node->setRevenue($node->getRevenue() + $product->getTotal());
                }
                foreach ($invoice->getInvoiceTreatments() as $treatment) {
                    $node->setServicesPaid($node->getServicesPaid() + $treatment->getTotal());
                    $node->setRevenue($node->getRevenue() + $product->getTotal());
                }
            }
        }
    }

}
