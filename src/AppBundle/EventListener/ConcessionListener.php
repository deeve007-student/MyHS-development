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
use AppBundle\Entity\ConcessionPriceOwner;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ConcessionListener
{

    use RecomputeChangesTrait;

    /** @var TokenStorage */
    protected $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {

            if ($entity instanceof Concession) {

                $concessionPriceOwners = $em->getRepository('AppBundle:ConcessionPriceOwner')->findAll();
                foreach ($concessionPriceOwners as $concessionPriceOwner) {

                    $concessionPrice = new ConcessionPrice();

                    $concessionPrice->setPrice(0);
                    $concessionPrice->setConcessionPriceOwner($concessionPriceOwner);
                    $concessionPrice->setConcession($entity);

                    if (!($this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getUser())) {
                        $concessionPrice->setOwner($concessionPriceOwner->getOwner());
                    }

                    $em->persist($concessionPrice);
                    $this->computeEntityChangeSet($concessionPrice, $em);
                }

            }

            if ($entity instanceof ConcessionPriceOwner && $entity->getConcessionPrices()->count() == 0) {
                $concessions = $em->getRepository('AppBundle:Concession')->findAll();

                foreach ($concessions as $concession) {
                    $concessionPrice = new ConcessionPrice();
                    $concessionPrice->setPrice(0);
                    $concessionPrice->setConcessionPriceOwner($entity);
                    $concessionPrice->setConcession($concession);

                    if (!($this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getUser())) {
                        $concessionPrice->setOwner($entity->getOwner());
                    }

                    $em->persist($concessionPrice);
                    $this->computeEntityChangeSet($concessionPrice, $em);
                }

            }

        }

    }

}
