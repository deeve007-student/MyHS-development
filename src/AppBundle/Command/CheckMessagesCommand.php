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
use AppBundle\Utils\MailgunUtils;
use AppBundle\Utils\MailUtils;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class CheckMessagesCommand
 */
class CheckMessagesCommand extends ContainerAwareCommand
{

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('app:check-messages-status')
            ->setDescription('Checks sent messages status');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Checking messages status...');

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        /** @var MailUtils $mailUtils */
        $mailUtils = $this->getContainer()->get('app.mail_utils');

        /** @var MailgunUtils $mailgunUtils */
        $mailgunUtils = $this->getContainer()->get('app.mailgun_utils');

        $messagesToCheck = $mailUtils->getNotDeliveredMessages(5);

        if (count($messagesToCheck) === 0) {
            $output->writeln('No messages to check');
            return;
        }

        $checkedMessages = 0;

        foreach ($messagesToCheck as $message) {

            if ($this->isMessageMature($message)) {

                $status = $mailgunUtils->getMessageStatus($message);
                $cantBeDelivered = $mailgunUtils->isMessageCantBeDelivered($status);

                $message->setStatus($status);

                if ($status === Message::STATUS_DELIVERED) {
                    $message->setDelivered(true);
                }

                if ($cantBeDelivered === true) {
                    $message->setDelivered(false);
                    $message->setBounced(true);
                }

                $checkedMessages++;
            }
        }

        $em->flush();

        $output->writeln($checkedMessages . ' messages updated');

    }

    /**
     * @param Message $message
     * @return bool
     */
    protected function isMessageMature(Message $message)
    {
        $interval = date_diff($message->getCreatedAt(), new \DateTime());
        $minutes = $interval->days * 24 * 60;
        $minutes += $interval->h * 60;
        $minutes += $interval->i;

        if ($minutes > $this->getContainer()->getParameter('message_status_check_interval')) {
            return true;
        }

        return false;

    }

}