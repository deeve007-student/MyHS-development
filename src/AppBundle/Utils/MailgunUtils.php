<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.05.2018
 * Time: 18:32
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Message;
use Mailgun\Mailgun;
use Mailgun\Model\Event\Event;

/**
 * Class MailgunUtils
 */
class MailgunUtils
{

    /** @var Mailgun */
    protected $mailgun;

    /** @var string */
    protected $domain;

    /**
     * MailgunUtils constructor.
     * @param $apiKey
     * @param $domain
     */
    public function __construct($apiKey, $domain)
    {
        $this->mailgun = Mailgun::create($apiKey);
        $this->domain = $domain;
    }

    /**
     * @return Mailgun
     */
    public function getMailgun()
    {
        return $this->mailgun;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param Message $message
     * @return string
     */
    public function getMessageStatus(Message $message)
    {
        $messageId = $message->getSid();
        $messageId = preg_replace('/^</', '', $messageId);
        $messageId = preg_replace('/>$/', '', $messageId);

        $queryString = [
            'message-id' => $messageId,
            'limit' => 1,
        ];

        $result = $this->mailgun->events()->get($this->domain, $queryString);
        /** @var Event $event */
        $event = $result->getItems()[0];
        $status = $event->getEvent();

        return $status;
    }

    /**
     * @param Message|string $messageOrStatus
     * @return bool
     */
    public function isMessageCantBeDelivered($messageOrStatus)
    {
        $messageStatus = $messageOrStatus;
        if ($messageOrStatus instanceof Message) {
            $messageStatus = $this->getMessageStatus($messageOrStatus);
        }

        if (in_array($messageStatus, [
            'rejected',
            'failed',
        ])) {
            return true;
        }

        return false;
    }

}