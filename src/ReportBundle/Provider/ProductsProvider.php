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
use AppBundle\Entity\Patient;
use AppBundle\Entity\Product;
use AppBundle\Utils\DateRangeUtils;
use AppBundle\Utils\DateTimeUtils;
use AppBundle\Utils\EventUtils;
use ReportBundle\Entity\ProductsNode;
use Doctrine\ORM\QueryBuilder;
use ReportBundle\Entity\Node;
use ReportBundle\Entity\NullObject;
use ReportBundle\Entity\PatientsNode;
use ReportBundle\Form\Type\DateRangeType;
use Symfony\Component\VarDumper\VarDumper;

class ProductsProvider extends AbstractReportProvider implements ReportProviderInterface
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
        $rootNode = new ProductsNode();

        if ($reportFormData['range'] == 'range') {
            $dateStart = DateTimeUtils::getDate($reportFormData['dateStart'])->setTimezone(new \DateTimeZone('UTC'));
            $dateEnd = DateTimeUtils::getDate($reportFormData['dateEnd'])->setTime(23, 59, 59);
        } else {
            list($dateStart, $dateEnd) = DateRangeType::getRangeDates($reportFormData['range']);
        }

        $products = $this->entityManager->getRepository('AppBundle:Product')->findAll();

        if ($reportFormData['nameOrCode']) {
            $products = array_filter($products, function (Product $product) use ($reportFormData) {
                if (mb_substr_count(mb_strtolower($product->getName()), mb_strtolower($reportFormData['nameOrCode']))
                    || mb_substr_count(mb_strtolower($product->getCode()), mb_strtolower($reportFormData['nameOrCode']))
                ) {
                    return true;
                }
                return false;
            });
        }

        if ($reportFormData['supplier']) {
            $products = array_filter($products, function (Product $product) use ($reportFormData) {
                if ($reportFormData['supplier'] == $product->getSupplier()) {
                    return true;
                }
                return false;
            });
        }

        if ($reportFormData['stockLevel']) {
            $products = array_filter($products, function (Product $product) use ($reportFormData) {
                if ($reportFormData['stockLevel'] == $product->getStockLevel()) {
                    return true;
                }
                return false;
            });
        }

        $invoices = $this->entityManager->getRepository('AppBundle:Invoice')->findAll();
        foreach ($products as $product) {
            $productNode = new ProductsNode();
            $productNode->setObject($product);

            /** @var Invoice $invoice */
            foreach ($invoices as $invoice) {
                if ($invoice->getDate() >= $dateStart && $invoice->getDate() <= $dateEnd) {
                    foreach ($invoice->getInvoiceProducts() as $invoiceProduct) {
                        if ($invoiceProduct->getProduct() === $product) {
                            $productNode->addQuantitySold($invoiceProduct->getQuantity());
                        }
                    }
                }
            }

            if ($productNode->getQuantitySold() > 0) {
                $rootNode->addChild($productNode);
            }
        }

        return $rootNode;
    }

}
