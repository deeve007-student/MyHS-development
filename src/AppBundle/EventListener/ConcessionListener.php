<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 23:47
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Concession;
use AppBundle\Entity\ConcessionPrice;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\Event\OnFlushEventArgs;

class ConcessionListener
{

    use RecomputeChangesTrait;

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {

            if ($entity instanceof Concession) {
                $concessionPriceOwners = $em->getRepository('AppBundle:ConcessionPriceOwner')->findAll();

                foreach ($concessionPriceOwners as $concessionPriceOwner) {
                    $concessionPrice = new ConcessionPrice();
                    $concessionPrice->setPrice($concessionPriceOwner->getPrice());
                    $concessionPrice->setConcessionPriceOwner($concessionPriceOwner);
                    $concessionPrice->setConcession($entity);

                    $em->persist($concessionPrice);
                    $this->computeEntityChangeSet($concessionPrice, $em);
                }

            }

        }

    }

}
