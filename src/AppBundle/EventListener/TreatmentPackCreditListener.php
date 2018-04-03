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
use AppBundle\Entity\TreatmentPackCredit;
use AppBundle\Event\AppointmentEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TreatmentPackCreditListener
{

    use RecomputeChangesTrait;

    protected $packToDelete;

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof InvoiceProduct && $entity->getProduct()->isPack()) {

                $packCredit = new TreatmentPackCredit();
                $packCredit->setAmountSpend(0);
                $packCredit->setInvoiceProduct($entity);
                $packCredit->setPatient($entity->getInvoice()->getPatient());

                $em->persist($packCredit);
                $this->computeEntityChangeSet($packCredit, $em);

            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof TreatmentPackCredit) {
                if ($entity->getAmountSpend() == ($entity->getProduct()->getPackAmount() * $entity->getInvoiceProduct()->getQuantity())) {
                    $this->packToDelete = $entity;
                }
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if ($this->packToDelete) {
            $packToDelete = $this->packToDelete;
            $this->packToDelete = null;
            $args->getEntityManager()->remove($packToDelete);
            $args->getEntityManager()->flush();
        }
    }

}
