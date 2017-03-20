<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 20.03.2017
 * Time: 13:10
 */

namespace AppBundle\Command;

use AppBundle\Entity\Invoice;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AclTestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:acl-test');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $patients = $em->getRepository('AppBundle:Patient')->findAll();
        foreach ($patients as $patient) {
            $output->writeln($patient->getId());
        }

    }
}
