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
use AppBundle\Utils\Hasher;

class RecallCommunicationListener
{

    use RecomputeChangesTrait;

    /** @var Hasher */
    protected $hasher;

    /** @var AppNotificator */
    protected $appNotificator;

    public function __construct(Hasher $hasher, AppNotificator $appNotificator)
    {
        $this->hasher = $hasher;
        $this->appNotificator = $appNotificator;
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
            $message->compile();

            $event->getEntityManager()->persist($message);
            $this->computeEntityChangeSet($message, $event->getEntityManager());
        }

    }

}
