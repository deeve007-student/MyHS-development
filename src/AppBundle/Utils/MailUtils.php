<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 22.03.2017
 * Time: 12:25
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Message;
use Doctrine\ORM\EntityManager;
use UserBundle\Entity\User;

/**
 * Class AppMailer
 */
class MailUtils
{

    /** @var EntityManager */
    protected $entityManager;

    /** @var string */
    protected $from;

    /** @var string */
    protected $fromName;

    /**
     * AppMailer constructor.
     * @param EntityManager $entityManager
     * @param string $from
     * @param string $fromName
     */
    public function __construct(EntityManager $entityManager, $from, $fromName)
    {
        $this->entityManager = $entityManager;
        $this->from = $from;
        $this->fromName = $fromName;
    }

    /**
     * @param null $bouncedFrom
     * @return array
     */
    public function createMessage($bouncedFrom = null)
    {
        $result = [
            'from' => $this->fromName . ' <' . $this->from . '>',
            'h:Reply-To' => $this->fromName . ' <' . $this->from . '>',
        ];

        if ($bouncedFrom) {
            $result = [
                'from' => $this->fromName . ' <' . $bouncedFrom . '>',
                'h:Reply-To' => $this->fromName . ' <' . $bouncedFrom . '>',
            ];
        }

        return $result;
    }

    /**
     * @param User $user
     * @return array
     */
    public function createPracticionerMessage(User $user)
    {
        $message = $this->createMessage();
        if ($user->getEmail()) {
            $message['from'] = (string)$user . ' <' . $user->getEmail() . '>';
            $message['h:Reply-To'] = (string)$user . ' <' . $user->getEmail() . '>';
        }
        return $message;
    }

    /**
     * @param null $limit
     * @return Message[]|array
     */
    public function getBouncedMessagesToSendBack($limit = null)
    {
        $qb = $this->entityManager->getRepository('AppBundle:Message')->createQueryBuilder('m')
            ->where('m.type = :emailType')
            ->andWhere('m.bounced = :true')
            ->andWhere('m.returned = :false')
            ->andWhere('m.delivered = :false')
            ->andWhere('m.parentMessage IS NULL')
            ->andWhere('m.owner IS NOT NULL')
            ->orderBy('m.updatedAt', 'ASC')
            ->setParameters([
                'true' => true,
                'false' => false,
                'emailType' => Message::TYPE_EMAIL,
            ]);

        if (is_int($limit) && $limit > 0) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param null $limit
     * @return Message[]|array
     */
    public function getNotSentMessages($limit = null)
    {
        return $this->entityManager->getRepository('AppBundle:Message')->findBy([
            'type' => Message::TYPE_EMAIL,
            'bounced' => false,
            'sent' => false,
            'delivered' => false,
            'parentMessage' => null,
        ], [
            'updatedAt' => 'ASC'
        ], $limit);
    }

    /**
     * @param int|null $limit
     * @return Message[]|array
     */
    public function getNotDeliveredMessages($limit = null)
    {
        $qb = $this->entityManager->getRepository('AppBundle:Message')->createQueryBuilder('m')
            ->where('m.type = :emailType')
            ->andWhere('m.bounced = :false')
            ->andWhere('m.sent = :true')
            ->andWhere('m.delivered = :false')
            ->andWhere('m.parentMessage IS NULL')
            ->orderBy('m.updatedAt', 'ASC')
            ->setParameters([
                'true' => true,
                'false' => false,
                'emailType' => Message::TYPE_EMAIL,
            ]);

        if (is_int($limit) && $limit > 0) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

}
