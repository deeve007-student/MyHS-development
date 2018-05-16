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
use Mailgun\Exception\HttpClientException;
use Mailgun\Exception\HttpServerException;
use Symfony\Component\VarDumper\VarDumper;
use Twilio\Rest\Client;

/**
 * Class AppNotificator
 * @package AppBundle\Utils
 */
class AppNotificator
{

    /** @var  MailgunUtils */
    protected $mailgunUtils;

    /** @var  MailUtils */
    protected $mailUtils;

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

    /**
     * AppNotificator constructor.
     * @param MailgunUtils $mailgunUtils
     * @param MailUtils $mailer
     * @param Client $twilio
     * @param TwilioUtils $twilioUtils
     * @param \Twig_Environment $twig
     * @param Formatter $formatter
     * @param EntityManager $entityManager
     */
    public function __construct(
        MailgunUtils $mailgunUtils,
        MailUtils $mailer,
        Client $twilio,
        TwilioUtils $twilioUtils,
        \Twig_Environment $twig,
        Formatter $formatter,
        EntityManager $entityManager
    )
    {
        $this->mailgunUtils = $mailgunUtils;
        $this->mailUtils = $mailer;
        $this->twilio = $twilio;
        $this->twilioUtils = $twilioUtils;
        $this->twig = $twig;
        $this->formatter = $formatter;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Message $message
     * @param bool $persist
     * @throws \Doctrine\ORM\OptimisticLockException
     * @return bool
     */
    public function sendMessage(Message $message, $persist = true)
    {
        if ($message->isCompiled() === false) {
            $message->compile($this->twig, $this->formatter);
        }

        try {
            $this->validateMessage($message);
        } catch (\Exception $exception) {
            return false;
        }

        if ($persist) {
            $this->entityManager->persist($message);
            $this->entityManager->flush();
        }

        switch ($message->getType()) {
            case Message::TYPE_EMAIL:
                $this->sendEmail($message);
                break;
            case Message::TYPE_SMS:
                $this->sendSms($message);
                break;
            default:
                break;
        }

        if ($persist) {
            $this->entityManager->flush();
        }

        return true;
    }

    /**
     * @param Message $message
     * @return bool
     */
    protected function sendEmail(Message $message)
    {
        $emailMessage = $this->mailUtils->createMessage($message->getBouncedFrom());

        if ($message->getOwner()) {
            $emailMessage = $this->mailUtils->createPracticionerMessage($message->getOwner());
        }

        $emailMessage['subject'] = $message->getSubject();
        $emailMessage['html'] = $message->getBody();
        $emailMessage['to'] = $message->getRecipientAddress();

        $message->setSent(true);

        try {
            $response = $this->mailgunUtils->getMailgun()->messages()->send(
                $this->mailgunUtils->getDomain(),
                $emailMessage
            );

            if ($response->getId()) {
                $message->setSid($response->getId());
            }
        } catch (HttpClientException $exception) {
            $message->setBounced(true);
        } catch (HttpServerException $exception) {
            $message->setSent(false);
        }

        return true;
    }

    /**
     * @param Message $message
     * @return bool
     */
    protected function sendSms(Message $message)
    {
        try {

            $sms = $this->twilio->messages->create(
                $message->getRecipientAddress(),
                [
                    "from" => '+61436412348',
                    "body" => $message->getBody()
                ]
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

    /**
     * @param Message $message
     * @throws \Exception
     */
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
