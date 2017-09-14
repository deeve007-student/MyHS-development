<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 18:23
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Message;
use AppBundle\Event\RecallEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use AppBundle\Utils\AppNotificator;
use AppBundle\Utils\Formatter;
use AppBundle\Utils\Hasher;

class RecallCommunicationListener
{

    use RecomputeChangesTrait;

    /** @var Hasher */
    protected $hasher;

    /** @var AppNotificator */
    protected $appNotificator;

    /** @var Formatter */
    protected $formatter;

    /** @var \Twig_Environment */
    protected $twig;

    public function __construct(
        Hasher $hasher,
        AppNotificator $appNotificator,
        Formatter $formatter,
        \Twig_Environment $twig
    )
    {
        $this->hasher = $hasher;
        $this->appNotificator = $appNotificator;
        $this->formatter = $formatter;
        $this->twig = $twig;
    }

    public function onRecallCreated(RecallEvent $event)
    {
        $entity = $event->getRecall();
        $patient = $entity->getPatient();

        $types = array();

        if ($entity->getRecallType()->isByCall()) {
            $types[] = Message::TYPE_CALL;
        }

        if ($entity->getRecallType()->isByEmail()) {
            $types[] = Message::TYPE_EMAIL;
        }

        if ($entity->getRecallType()->isBySms()) {
            $types[] = Message::TYPE_SMS;
        }

        foreach ($types as $messageType) {
            $message = new Message($messageType);
            $message->setTag(Message::TAG_RECALL)
                ->setRecipient($patient);
            //->overrideDates();

            //$message->setCreatedAt($entity->getDate());
            $message->compile($this->twig, $this->formatter);

            $event->getEntityManager()->persist($message);
            $this->computeEntityChangeSet($message, $event->getEntityManager());
        }

    }

}
