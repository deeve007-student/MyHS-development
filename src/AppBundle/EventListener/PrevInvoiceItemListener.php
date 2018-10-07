<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 11.03.18
 * Time: 15:03
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoiceProduct;
use AppBundle\Entity\InvoiceTreatment;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;

/**
 * Class PrevInvoiceItemListener
 *
 * When new invoice is created, this listener checks items added from prev Draft/Overdue/Pending invoices
 * and removes these empty prev invoices
 */
class PrevInvoiceItemListener
{

    use RecomputeChangesTrait;

    protected $invoice;

    /** @var array */
    protected $itemsToRemove = [];

    /** @var array */
    protected $invoicesToRemove = [];

    /**
     * @param OnFlushEventArgs $args
     */
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
        /** @var InvoiceProduct[]|InvoiceTreatment[] $invoiceItems */
        $invoiceItems = $em->getRepository(get_class($entity))->findAll();
        $invoiceItems = array_filter($invoiceItems, function ($invoiceItem) use ($entity) {

            if (!in_array($invoiceItem->getInvoice()->getStatus(), [
                    Invoice::STATUS_DRAFT,
                    Invoice::STATUS_PENDING,
                    Invoice::STATUS_OVERDUE,
                ]) || !$invoiceItem->getInvoice()->getPatient()) {
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
                $this->itemsToRemove[] = array(
                    'item' => $invoiceItem,
                    'newInvoice' => $entity->getInvoice(),
                );
            }
            $this->recomputeEntityChangeSet($invoiceItem, $em);
        }

        $entity->setFromOtherInvoice(false);

        $this->recomputeEntityChangeSet($entity, $em);
    }

    /**
     * @param PostFlushEventArgs $args
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postFlush(PostFlushEventArgs $args)
    {
        $em = $args->getEntityManager();

        if (count($this->itemsToRemove) > 0) {

            foreach ($this->itemsToRemove as $data) {

                $item = $data['item'];
                $newInvoice = $data['newInvoice'];

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

                // If old invoice dont have any products - remove invoice
                // and link old appointment to new invoice

                $em->remove($item);

                if (count($invoiceItems) === 0) {
                    foreach ($invoice->getAppointmentPatients() as $appointmentPatient) {
                        $appointmentPatient->setInvoice($newInvoice);
                    }
                    $this->invoicesToRemove[] = $invoice;
                }
            }

            $this->itemsToRemove = [];
            $em->flush();
        }

        if (count($this->invoicesToRemove) > 0) {

            foreach ($this->invoicesToRemove as $invoice) {
                $sql = "DELETE FROM `invoice` WHERE `id`='".$invoice->getId()."'";
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();
            }

            $this->invoicesToRemove = [];
        }

    }

}
