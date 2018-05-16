<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 16.05.2018
 * Time: 10:04
 */

namespace AppBundle\Command;

use AppBundle\Utils\AppNotificator;
use AppBundle\Utils\MailUtils;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ResendStuckMessagesCommand
 */
class ResendStuckMessagesCommand extends ContainerAwareCommand
{

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('app:resend-stuck-messages')
            ->setDescription('Resends messages that was not send');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Resending messages...');

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        /** @var MailUtils $mailUtils */
        $mailUtils = $this->getContainer()->get('app.mail_utils');

        /** @var AppNotificator $appNotificator */
        $appNotificator = $this->getContainer()->get('app.notificator');

        $messagesToResend = $mailUtils->getNotSentMessages(5);

        if (count($messagesToResend) === 0) {
            $output->writeln('No messages to resend');
            return;
        }

        $resentMessages = 0;

        foreach ($messagesToResend as $message) {
            $appNotificator->sendMessage($message, false);
            $resentMessages++;
        }

        $em->flush();

        $output->writeln($resentMessages . ' messages resent');

    }

}