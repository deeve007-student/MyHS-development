<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 14.03.2017
 * Time: 16:02
 */

namespace AppBundle\Command;

use AppBundle\Entity\Invoice;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InvoiceOverdueCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:invoice-overdue')
            ->setDescription('Transit invoices to overdue status');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Processing invoices');

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $overdueInvoices = 0;
        $now = new \DateTime();

        $qb = $em->getRepository('AppBundle:Invoice')->createQueryBuilder('i');
        $invoices = $qb->where('i.status != :paidStatus')
            ->andWhere('i.status != :overdueStatus')
            ->setParameter('paidStatus', Invoice::STATUS_PAID)
            ->setParameter('overdueStatus', Invoice::STATUS_OVERDUE)
            ->getQuery()->getResult();

        if ($invoices) {
            /** @var Invoice $invoice */
            foreach ($invoices as $invoice) {
                if ($now > $invoice->getDueDateComputed()) {
                    $invoice->setStatus(Invoice::STATUS_OVERDUE);
                    $overdueInvoices++;
                }
            }
        }

        $em->flush();

        $output->writeln('Invoices set to overdue: '.$overdueInvoices);
    }
}
