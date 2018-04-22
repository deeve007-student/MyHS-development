<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 22.03.2017
 * Time: 12:25
 */

namespace AppBundle\Utils;

use UserBundle\Entity\User;

class AppMailer
{

    /** @var  \Swift_Mailer */
    protected $mailer;

    /** @var string */
    protected $from;

    /** @var string */
    protected $fromName;

    /** @var string */
    protected $realTransport;

    public function __construct(\Swift_Mailer $mailer, $realTransport, $from, $fromName)
    {
        $this->mailer = $mailer;
        $this->realTransport = $realTransport;
        $this->from = $from;
        $this->fromName = $fromName;
    }

    /**
     * @return \Swift_Message
     */
    public function createMessage()
    {
        return \Swift_Message::newInstance()
            ->setFrom($this->from, $this->fromName)
            ->setReplyTo($this->from, $this->fromName);
    }

    /**
     * @param User $user
     * @return \Swift_Message
     */
    public function createPracticionerMessage(User $user)
    {
        $message = $this->createMessage();
        if ($user->getEmail()) {
            $message->setFrom($user->getEmail(), (string)$user)
                ->setReplyTo($user->getEmail(), (string)$user);
        }

        return $message;
    }

    public function send(\Swift_Message $message, $flushSpool = false)
    {
        try {
            $result = $this->mailer->send($message);

            if ($flushSpool) {
                $transport = $this->mailer->getTransport();
                $spool = $transport->getSpool();
                $spool->flushQueue($this->realTransport);
            }

        } catch (\Exception $exception) {
            return false;
        }

        return $result;
    }

}
