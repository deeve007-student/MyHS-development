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
use AppBundle\Entity\InvoiceTreatment;
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

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof InvoicePayment) {
                $this->invoice = $entity->getInvoice();
            }

            if ($entity instanceof InvoiceTreatment) {
                $this->invoice = $entity->getInvoice();
            }

            if ($entity instanceof InvoiceProduct) {
                $this->invoice = $entity->getInvoice();
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof InvoicePayment) {
                $this->invoice = $entity->getInvoice();
            }

            if ($entity instanceof InvoiceTreatment) {
                $this->invoice = $entity->getInvoice();
            }

            if ($entity instanceof InvoiceProduct) {
                $this->invoice = $entity->getInvoice();
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof InvoicePayment) {
                $this->invoice = $entity->getInvoice();
            }

            if ($entity instanceof InvoiceTreatment) {
                $this->invoice = $entity->getInvoice();
            }

            if ($entity instanceof InvoiceProduct) {
                $this->invoice = $entity->getInvoice();
            }
        }
    }

    protected function recalculateInvoiceStatus(Invoice $invoice, EntityManager $em)
    {
        if ($invoice->getAmountDue() <= 0) {
            $invoice->setStatus(Invoice::STATUS_PAID);
        }
        if ($invoice->getAmountDue() > 0 && $invoice->getStatus() == Invoice::STATUS_PAID) {
            $invoice->setStatus(Invoice::STATUS_PENDING);
        }
        $this->recomputeEntityChangeSet($invoice, $em);
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if ($this->invoice) {
            $invoiceToRecalculate = $this->invoice;
            $this->invoice = null;
            $this->recalculateInvoiceStatus($invoiceToRecalculate, $args->getEntityManager());
            $args->getEntityManager()->flush();
        }
    }

}
