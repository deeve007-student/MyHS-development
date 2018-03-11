<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 11.03.18
 * Time: 15:03
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoicePayment;
use AppBundle\Entity\InvoiceProduct;
use AppBundle\Entity\InvoiceTreatment;
use AppBundle\Event\AppointmentEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PrevInvoiceItemListener
{

    use RecomputeChangesTrait;

    protected $invoice;

    /** @var array */
    protected $itemsToRemove = array();

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof InvoiceTreatment
                || $entity instanceof InvoiceProduct) {
                if ($entity->isFromOtherInvoice()) {
                    $this->processNewInvoiceItemCopied($entity, $em);
                }
            }
        }
    }

    /**
     * @param InvoiceProduct|InvoiceTreatment $entity
     * @param EntityManager $em
     */
    protected function processNewInvoiceItemCopied($entity, EntityManager $em)
    {
        $invoiceItems = $em->getRepository(get_class($entity))->findAll();
        $invoiceItems = array_filter($invoiceItems, function ($invoiceItem) use ($entity) {

            if ($invoiceItem->getInvoice()->getStatus() !== Invoice::STATUS_PENDING) {
                return false;
            }

            return $invoiceItem->getInvoice()->getPatient()->getId() === $entity->getInvoice()->getPatient()->getId();
        });

        $sum = 0;
        foreach ($invoiceItems as $invoiceItem) {
            $sum += $invoiceItem->getTotal();
        }

        foreach ($invoiceItems as $invoiceItem) {
            $ratio = $invoiceItem->getTotal() / $sum;
            $invoiceItem->setPrice($invoiceItem->getPrice() - (($entity->getTotal() * $ratio) / $invoiceItem->getQuantity()));
            if ($invoiceItem->getTotal() <= 0) {
                $this->itemsToRemove[] = $invoiceItem;
            }
            $this->recomputeEntityChangeSet($invoiceItem, $em);
        }

        $entity->setFromOtherInvoice(false);

        $this->recomputeEntityChangeSet($entity, $em);
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $em = $args->getEntityManager();

        if (count($this->itemsToRemove) > 0) {
            foreach ($this->itemsToRemove as $item) {

                /** @var Invoice $invoice */
                $invoice = $item->getInvoice();

                if ($item instanceof InvoiceTreatment) {
                    $invoice->removeInvoiceTreatment($item);
                }
                if ($item instanceof InvoiceProduct) {
                    $invoice->removeInvoiceProduct($item);
                }

                $invoiceItems = array_merge(
                    $invoice->getInvoiceTreatments()->toArray(),
                    $invoice->getInvoiceProducts()->toArray()
                );

                $em->remove($item);
                if (!count($invoiceItems)) {
                    $em->remove($invoice);
                }
            }

            $this->itemsToRemove = array();
            $em->flush();
        }
    }

}
