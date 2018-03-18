<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 02.07.2017
 * Time: 22:47
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoicePayment;
use AppBundle\Entity\InvoiceProduct;
use AppBundle\Entity\InvoiceRefund;
use AppBundle\Entity\InvoiceTreatment;
use AppBundle\Entity\Refund;
use AppBundle\Event\AppointmentEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class InvoiceStatusListener
{

    use RecomputeChangesTrait;

    protected $invoice;

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach (array_merge(
                     $uow->getScheduledEntityInsertions(),
                     $uow->getScheduledEntityDeletions(),
                     $uow->getScheduledEntityUpdates()
                 ) as $entity) {
            if (in_array(get_class($entity), array(
                    InvoicePayment::class,
                    InvoiceTreatment::class,
                    InvoiceProduct::class,
                    Refund::class,
                )) && !in_array($entity->getInvoice(), $uow->getScheduledEntityDeletions())) {
                $this->invoice = $entity->getInvoice();
            }
        }
    }

    public function recalculateInvoiceStatus(Invoice $invoice, EntityManager $em)
    {
        if ($invoice->getTotal() > 0 && $invoice->getAmountDue() <= 0) {
            $invoice->setStatus(Invoice::STATUS_PAID);
        }
        if ($invoice->getAmountDue() > 0 && ($invoice->getStatus() == Invoice::STATUS_PAID || $invoice->getStatus() == Invoice::STATUS_DRAFT)) {
            $invoice->setStatus(Invoice::STATUS_PENDING);
        }

        if ($invoice->getRefundsSum() > 0) {
            $invoice->setStatus(Invoice::STATUS_REFUNDED_PART);

            if ($invoice->getPaymentsSum()-$invoice->getRefundsSum() <= 0) {
                $invoice->setStatus(Invoice::STATUS_REFUNDED);
            }
        }

        if ($invoice->getStatus() == Invoice::STATUS_PAID) {
            if (!$invoice->getPaidDate()) {
                $invoice->setPaidDate(new \DateTime());
            }
        } else {
            $invoice->setPaidDate(null);
        }

        $this->recomputeEntityChangeSet($invoice, $em);
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if ($this->invoice) {
            $invoiceToRecalculate = $this->invoice;
            $this->invoice = null;
            $args->getEntityManager()->flush();
            $this->recalculateInvoiceStatus($invoiceToRecalculate, $args->getEntityManager());
            $args->getEntityManager()->flush();
        }
    }

}
