<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 02.07.2017
 * Time: 22:47
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\InvoiceRefund;
use AppBundle\Entity\Refund;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;

class RefundListener
{

    use RecomputeChangesTrait;

    /** @var Refund */
    protected $refund;

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof InvoiceRefund) {
                $this->refund = $entity->getRefund();
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if ($this->refund) {
            $refund = $this->refund;
            $this->refund = null;
            if ($refund->getItems()->count() == 0) {
                $args->getEntityManager()->remove($refund);
                $args->getEntityManager()->flush();
            }
        }
    }

}
