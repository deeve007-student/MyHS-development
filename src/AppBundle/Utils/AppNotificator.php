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
use Twilio\Rest\Client;

class AppNotificator
{

    /** @var  AppMailer */
    protected $mailer;

    /** @var  Client */
    protected $twilio;

    /** @var  TwilioUtils */
    protected $twilioUtils;

    /** @var  \Twig_Environment */
    protected $twig;

    /** @var  Formatter */
    protected $formatter;

    /** @var  EntityManager */
    protected $entityManager;

    public function __construct(
        AppMailer $mailer,
        Client $twilio,
        TwilioUtils $twilioUtils,
        \Twig_Environment $twig,
        Formatter $formatter,
        EntityManager $entityManager
    )
    {
        $this->mailer = $mailer;
        $this->twilio = $twilio;
        $this->twilioUtils = $twilioUtils;
        $this->twig = $twig;
        $this->formatter = $formatter;
        $this->entityManager = $entityManager;
    }

    public function sendMessage(Message $message, $persist = true)
    {
        $message->compile($this->twig, $this->formatter);

        try {
            $this->validateMessage($message);
        } catch (\Exception $e) {
            return false;
        }

        $result = false;

        switch ($message->getType()) {
            case Message::TYPE_EMAIL:
                $result = $this->sendEmail($message);
                break;
            case Message::TYPE_SMS:
                $result = $this->sendSms($message);
                break;
            default:

                break;
        }

        if ($persist) {
            $this->entityManager->persist($message);
            $this->entityManager->flush();
        }

        return true;
    }

    protected function sendEmail(Message $message)
    {
        $emailMessage = $this->mailer->createMessage();
        if ($message->getOwner()) {
            $emailMessage = $this->mailer->createPracticionerMessage($message->getOwner());
        }

        $emailMessage->setSubject($message->getSubject())
            ->setBody($message->getBody())
            ->setTo($message->getRecipientAddress());

        if ($this->mailer->send($emailMessage, true)) {
            return true;
        }

        return false;
    }

    protected function sendSms(Message $message)
    {
        try {

            $sms = $this->twilio->messages->create(
                $message->getRecipientAddress(),
                array(
                    "from" => '+61436412348',
                    "body" => $message->getBody()
                )
            );

            $message->setSid($sms->sid)
                ->setPrice($sms->price);

            if (!$sms->errorCode) {
                return true;
            }
            $message->setError($sms->errorMessage);

            return false;

        } catch (\Exception $e) {
            return false;
        }
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
