<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 16.05.2018
 * Time: 10:04
 */

namespace AppBundle\Command;

use AppBundle\Entity\Message;
use AppBundle\Utils\AppNotificator;
use AppBundle\Utils\MailUtils;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SendBackBouncedMessagesCommand
 */
class ReturnBouncedMessagesCommand extends ContainerAwareCommand
{

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('app:return-bounced-messages')
            ->setDescription('Sends back bounced messages');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Returning bounced messages...');

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        /** @var MailUtils $mailUtils */
        $mailUtils = $this->getContainer()->get('app.mail_utils');

        /** @var AppNotificator $appNotificator */
        $appNotificator = $this->getContainer()->get('app.notificator');

        $messagesToSendBack = $mailUtils->getBouncedMessagesToSendBack(5);

        if (count($messagesToSendBack) === 0) {
            $output->writeln('No messages to resend');
            return;
        }

        $sentBackMessages = 0;

        foreach ($messagesToSendBack as $message) {
            $backMessage = new Message();
            $backMessage->setRecipient($message->getOwner())
                ->setBodyData($message->getBody())
                ->setSubject('Bounced: ' . $message->getSubject())
                ->setBouncedFrom($message->getRecipientAddress());

            $message->setReturned(true);

            $appNotificator->sendMessage($backMessage, false);
            $sentBackMessages++;
        }

        $em->flush();

        $output->writeln($sentBackMessages . ' messages returned');

    }

}