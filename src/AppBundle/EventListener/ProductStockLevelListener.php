<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 17.07.2017
 * Time: 20:56
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\InvoiceProduct;
use AppBundle\Event\AppointmentEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ProductStockLevelListener
{

    use RecomputeChangesTrait;

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {

            if ($entity instanceof InvoiceProduct) {
                $product = $entity->getProduct();
                $product->setStockLevel($product->getStockLevel() - $entity->getQuantity());
                $this->computeEntityChangeSet($product, $em);
            }

        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {

            if ($entity instanceof InvoiceProduct) {
                $product = $entity->getProduct();
                $product->setStockLevel($product->getStockLevel() + $entity->getQuantity());
                $this->computeEntityChangeSet($product, $em);
            }

        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {

            if ($entity instanceof InvoiceProduct) {

                if ($entity instanceof InvoiceProduct) {
                    if (array_key_exists('quantity', $uow->getEntityChangeSet($entity))) {
                        $oldQuantity = $uow->getEntityChangeSet($entity)['quantity'][0];
                        $newQuantity = $uow->getEntityChangeSet($entity)['quantity'][1];
                        $delta = abs($newQuantity - $oldQuantity);

                        $product = $entity->getProduct();
                        if ($newQuantity > $oldQuantity) {
                            $product->setStockLevel($product->getStockLevel() - $delta);
                        }
                        if ($newQuantity < $oldQuantity) {
                            $product->setStockLevel($product->getStockLevel() + $delta);
                        }
                        $this->computeEntityChangeSet($product, $em);
                    }
                }
            }

        }
    }

}
