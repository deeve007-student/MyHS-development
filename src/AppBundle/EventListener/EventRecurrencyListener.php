<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 10.06.2018
 * Time: 12:46
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventRecurrency;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use AppBundle\Utils\EventRecurrencyGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class EventRecurrencyListener
 */
class EventRecurrencyListener
{

    use RecomputeChangesTrait;

    /** @var RecursiveValidator */
    protected $validator;

    /** @var EventRecurrency */
    protected $recurrency;

    /** @var EventRecurrencyGenerator */
    protected $recurrencyGenerator;

    /**
     * EventRecurrencyListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->validator = $container->get('validator');
        $this->recurrencyGenerator = $container->get('app.event_recurrency_generator');
    }

    /**
     * @param Event $thisEvent
     * @return Event[]|ArrayCollection
     */
    public function getAffectedEvents(Event $thisEvent)
    {
        /** @var Event[]|ArrayCollection $events */
        $events = new ArrayCollection();

        if ($thisEvent->getAffect() == EventRecurrency::AFFECT_ALL) {
            $events = new ArrayCollection($thisEvent->getRecurrency()->getEvents()->toArray());
        } else if ($thisEvent->getAffect() == EventRecurrency::AFFECT_THIS_AND_FOLLOWING) {
            $events = new ArrayCollection(array_filter($thisEvent->getRecurrency()->getEvents()->toArray(),
                function (Event $event) use ($thisEvent) {
                    if ($event->getStart() >= $thisEvent->getStart()) {
                        return true;
                    }
                    return false;
                }));
        }

        $events->removeElement($thisEvent);

        return $events;
    }

    /**
     * @param OnFlushEventArgs $args
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $event) {
            if ($event instanceof Event) {

                $recurrency = $event->getRecurrency();

                if (is_null($recurrency->getDateStart())) {
                    $recurrency->setDateStart($event->getStart());
                    $recurrency->setLastEventDate($event->getStart());
                    $this->recomputeEntityChangeSet($recurrency, $em);
                }

            }
        }

        foreach ($uow->getScheduledEntityInsertions() as $recurrency) {
            if ($recurrency instanceof EventRecurrency) {
                $this->recurrency = $recurrency;
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $thisEvent) {
            if ($thisEvent instanceof Event) {
                $this->recurrency = $thisEvent->getRecurrency();
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $event) {
            if ($event instanceof Event) {

                if (false === $event->isSkipChangesetCheck()) {

                    $events = $this->getAffectedEvents($event);
                    $this->handleRecurrencyModeChange($event, $em, $uow);
                    $this->affectOtherEvents($event, $events, $em, $uow);
                }
            }
        }

    }

    /**
     * @param Event $event
     * @param EntityManager $em
     * @param UnitOfWork $uow
     */
    protected function handleRecurrencyModeChange(Event $event, EntityManager $em, UnitOfWork $uow) {
        $recurrency = $event->getRecurrency();
        $uow->computeChangeSet($em->getClassMetadata(EventRecurrency::class), $recurrency);
        $recurrencyChangeset = $uow->getEntityChangeSet($recurrency);

        if (array_key_exists('type', $recurrencyChangeset) && $recurrencyChangeset['type'][1] !== $recurrencyChangeset['type'][0]) {
            $mainEvent = $recurrency->getEvents()->first();
            $eventsToRemove = $recurrency->getEvents();
            $eventsToRemove->removeElement($mainEvent);
            foreach ($eventsToRemove as $event) {
                $em->remove($event);
            }
            $recurrency->setLastEventDate($mainEvent->getStart());
        }
    }

    /**
     * Distribute event changeset to events collection (events related to recurrency)
     *
     * @param Event $thisEvent
     * @param Collection $events
     * @param EntityManager $em
     * @param UnitOfWork $uow
     */
    protected function affectOtherEvents(Event $thisEvent, Collection $events, EntityManager $em, UnitOfWork $uow)
    {

        $changeset = $uow->getEntityChangeSet($thisEvent);
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($events as $relatedEvent) {

            $relatedEvent->setSkipChangesetCheck(true);

            foreach ($changeset as $property => $values) {
                if ($property == "start" || $property == "end") {

                    if ($values[0]->format('H:i:s') !== $values[1]->format('H:i:s')) {

                        /** @var \DateTime $dateOld */
                        $dateOld = $accessor->getValue($relatedEvent, $property);

                        /** @var \DateTime $dateNew */
                        $dateNew = (clone $values[1]);

                        $dateNew->setDate(
                            $dateOld->format('Y'),
                            $dateOld->format('m'),
                            $dateOld->format('d')
                        );

                        $accessor->setValue($relatedEvent, $property, $dateNew);

                    }

                    continue;
                }

                $accessor->setValue($relatedEvent, $property, $values[1]);
            }

            if (count($this->validator->validate($relatedEvent)) == 0) {
                $this->computeEntityChangeSet($relatedEvent, $em);
            }

        }
    }

    /**
     * @param PostFlushEventArgs $args
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Recurr\Exception\InvalidArgument
     * @throws \Recurr\Exception\InvalidRRule
     * @throws \Recurr\Exception\InvalidWeekday
     */
    public function postFlush(PostFlushEventArgs $args)
    {
        $em = $args->getEntityManager();

        if (!is_null($this->recurrency)) {

            $recurrency = $this->recurrency;
            $this->recurrency = null;

            // If recurrency has no events - remove this recurrency
            if ($recurrency->getEvents()->count() == 0) {
                $em->remove($recurrency);
                $em->flush();
                return;
            }

            // Otherwise - create recurring events based on recurrency settings
            foreach ($this->recurrencyGenerator->generateRecurringEvents($recurrency, $recurrency->getLastEventDate(), false) as $event) {
                $em->persist($event);
                $this->computeEntityChangeSet($event, $em);
            }

            $this->computeEntityChangeSet($recurrency, $em);
            $em->flush();
        }
    }

}