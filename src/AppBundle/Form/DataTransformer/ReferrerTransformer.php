<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 11.06.2017
 * Time: 13:43
 */

namespace AppBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;

class ReferrerTransformer implements DataTransformerInterface
{
    /** @var EntityManager */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($value)
    {
        if ($patient = $this->entityManager->getRepository('AppBundle:Patient')->find((int)$value)) {
            return (string)$patient;
        }

        return $value;
    }

    public function reverseTransform($value)
    {
        $qb = $this->entityManager->getRepository('AppBundle:Patient')->createQueryBuilder('p');

        if ($patients = $qb->where(
            "CONCAT(CONCAT(CONCAT(CONCAT(p.title,' '),p.firstName),' '),p.lastName) = :fullName"
        )->setParameter('fullName', trim($value))
            ->setMaxResults(1)
            ->getQuery()->getResult()
        ) {
            return $patients[0]->getId();
        }

        return $value;
    }
}
