<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 12.09.2017
 * Time: 13:03
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Message;
use Doctrine\ORM\EntityManager;

class AppNotificator
{

    /** @var  AppMailer */
    protected $mailer;

    /** @var  \Twig_Environment */
    protected $twig;

    /** @var  EntityManager */
    protected $entityManager;

    public function __construct(
        AppMailer $mailer,
        \Twig_Environment $twig,
        EntityManager $entityManager
    )
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }

    public function sendMessage(Message $message, $persist = true)
    {
        $message->compile($this->twig);
        $this->validateMessage($message);
        if ($persist) {
            $this->entityManager->persist($message);
            $this->entityManager->flush();
        }
        return true;
    }

    protected function validateMessage(Message $message)
    {
        if (!$message->getRecipientAddress()) {
            throw new \Exception('Recipient address not specified');
        }
        if (!$message->getBody()) {
            throw new \Exception('Message body not specified');
        }
    }

}
