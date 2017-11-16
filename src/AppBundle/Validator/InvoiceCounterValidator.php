<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.11.17
 * Time: 16:56
 */

namespace AppBundle\Validator;

use AppBundle\Entity\CalendarSettings;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoiceSettings;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class InvoiceCounterValidator extends ConstraintValidator
{
    /** @var  EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($invoiceSettings, Constraint $constraint)
    {

        $qb = $this->entityManager->getRepository('AppBundle:Invoice')->createQueryBuilder('i');

        /** @var Invoice[] $existedInvoices */
        $existedInvoices = $qb->where('i.owner = :owner')
            ->setParameter('owner', $invoiceSettings->getOwner())
            ->getQuery()->getResult();

        $maxInvoiceNumber = 0;
        foreach ($existedInvoices as $existedInvoice) {
            $number = (int)$existedInvoice->getName();
            if ($number > $maxInvoiceNumber) {
                $maxInvoiceNumber = $number;
            }
        }

        /** @var InvoiceSettings $invoiceSettings */
        if ($invoiceSettings->getInvoiceNumber()<=$maxInvoiceNumber) {
            $this->context->buildViolation($constraint->message)
                ->atPath('invoiceNumber')
                ->addViolation();
        }
    }
}
