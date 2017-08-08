<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 08.08.2017
 * Time: 17:18
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTasksCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('app:tasks-generate')
            ->setDescription('Generates tasks according to existed RecurringTasks objects');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Generating tasks');

        $tasksCreated = $this->getContainer()->get('app.task_utils')->generateTasks();

        $output->writeln('Tasks created: ' . $tasksCreated);
    }
}
