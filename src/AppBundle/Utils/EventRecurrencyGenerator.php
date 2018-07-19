<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 10.06.2018
 * Time: 16:58
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventRecurrency;
use Doctrine\ORM\EntityManager;
use Recurr\Transformer\ArrayTransformer;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class EventRecurrencyGenerator
 */
class EventRecurrencyGenerator
{

    /** @var RecursiveValidator */
    protected $validator;

    /** @var EntityManager */
    protected $entityManager;

    /**
     * EventRecurrencyGenerator constructor.
     * @param RecursiveValidator $validator
     */
    public function __construct(RecursiveValidator $validator, EntityManager $entityManager)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * @param EventRecurrency $recurrency
     * @param \DateTime $generationStartDate
     * @param bool $flush
     * @return array
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Recurr\Exception\InvalidArgument
     * @throws \Recurr\Exception\InvalidRRule
     * @throws \Recurr\Exception\InvalidWeekday
     */
    public function generateRecurringEvents(EventRecurrency $recurrency, \DateTime $generationStartDate, $flush = true)
    {
        $newEvents = [];

        /** @var Event $event */
        $event = $recurrency->getLastEvent();

        $transformer = new ArrayTransformer();
        $rule = $recurrency->getRule($generationStartDate);

        $occurrences = $transformer->transform($rule);

        VarDumper::dump($event);

        for ($n = 0; $n < $occurrences->count(); $n++) {

            $occurrence = $occurrences[$n];

            $newEvent = clone $event;

            $start = (clone $event->getStart())->setDate(
                $occurrence->getStart()->format('Y'),
                $occurrence->getStart()->format('m'),
                $occurrence->getStart()->format('d')
            );

            $end = (clone $event->getEnd())->setDate(
                $occurrence->getStart()->format('Y'),
                $occurrence->getStart()->format('m'),
                $occurrence->getStart()->format('d')
            );

            $newEvent->setResource($event->getResource());
            $newEvent->setStart($start);
            $newEvent->setEnd($end);

            if (
                $recurrency->getLastEventDate() <= $newEvent->getStart()
                && count($this->validator->validate($newEvent)) == 0
            ) {
                $this->entityManager->persist($newEvent);
                $newEvents[] = $newEvent;
                $lastEventDate = (clone ($newEvent->getStart()))->modify('+1 day');
                if ($lastEventDate > $recurrency->getLastEventDate()) {
                    $recurrency->setLastEventDate($lastEventDate);
                }
                if ($event->getStart() < $recurrency->getDateStart()) {
                    $recurrency->setDateStart($event->getStart());
                }
            }
        }

        if (true === $flush) {
            $this->entityManager->flush();
        }

        return $newEvents;
    }

}