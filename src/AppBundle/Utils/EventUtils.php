<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 06.06.2017
 * Time: 11:46
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Event;
use AppBundle\Entity\EventResource;
use AppBundle\Entity\Patient;
use AppBundle\Entity\UnavailableBlock;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\Translator;

class EventUtils
{
    /** @var  Hasher */
    protected $hasher;

    /** @var  Translator */
    protected $translator;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Session */
    protected $session;

    /** @var  RequestStack */
    protected $requestStack;

    public function __construct(
        EntityManager $entityManager,
        Hasher $hasher,
        Translator $translator,
        RequestStack $requestStack
    )
    {
        $this->hasher = $hasher;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function getInterval()
    {
        return '15';
    }

    public function getDaysToShow()
    {
        $days = 5;

        if ($this->requestStack->getCurrentRequest()->getSession()->has('calendar_range')) {
            $days = $this->requestStack->getCurrentRequest()->getSession()->get('calendar_range');
        }

        if ($days == 5) {
            return array('week', 5, 'false');
        }

        $settings = array('day', $days, 'true');

        return $settings;
    }

    public function getDayStart()
    {
        return '08:00';
    }

    public function getDayEnd()
    {
        return '20:00';
    }

    public function getBusinessDayStart()
    {
        return '10:00';
    }

    public function getBusinessDayEnd()
    {
        return '18:00';
    }

    public function serializeEvent(Event $event)
    {
        $eventData = array(
            'id' => $this->hasher->encodeObject($event, ClassUtils::getParentClass($event)),
            'class' => get_class($event),
            'title' => (string)$event,
            'description' => $event->getDescription() ? $event->getDescription() : '',
            'start' => $event->getStart()->format(\DateTime::ATOM),
            'end' => $event->getEnd()->format(\DateTime::ATOM),
            'editable' => 1,
            'color' => '#D3D3D3',
            'textColor' => '#000',
            'column' => $this->getResourceNumber($event->getResource()),
            'birthday' => false,
            'arrived' => false,
        );

        switch (get_class($event)) {
            case Appointment::class:
                /** @var Appointment $event */
                $eventData['tag'] = (string)$event->getTreatment();

                if ($event->getPatient()->getDateOfBirth()->format('md') == $event->getStart()->format('md')) {
                    $eventData['birthday'] = true;
                }

                if ($color = $event->getTreatment()->getCalendarColour()) {
                    $eventData['color'] = $color;
                    $eventData['textColor'] = '#fff';
                }

                if ($event->getPatientArrived()) {
                    $eventData['arrived'] = true;
                    $eventData['className'] = 'event-arrived';
                }

                break;
            case UnavailableBlock::class:
                $eventData['title'] = $this->translator->trans('app.unavailable_block.tag');
                $eventData['color'] = '#67b4be';
                $eventData['textColor'] = '#fff';
                break;
        }

        return $eventData;
    }

    public function getResourceNumber(EventResource $resource)
    {
        return array_search($resource, $this->getResources());
    }

    public function getResourceByNumber($number)
    {
        return $this->getResources()[$number];
    }

    public function getResources()
    {
        return $this->entityManager->getRepository('AppBundle:EventResource')->createQueryBuilder('r')
            ->orderBy('r.position', 'ASC')->getQuery()->getResult();
    }

    public function getEventsQb($class = Event::class)
    {
        return $this->entityManager->getRepository($class)->createQueryBuilder('a');
    }

    public function getActiveEventsQb($class = Event::class)
    {
        $qb = $this->entityManager->getRepository($class)->createQueryBuilder('a');
        $qb->leftJoin(Appointment::class, 'app', 'WITH', 'a.id = app.id')
            ->where($qb->expr()->isNull('app.reason'));
        return $qb;
    }

    public function getNextAppointmentsQb(Appointment $appointment = null)
    {
        $qb = $this->getActiveEventsQb(Appointment::class);

        $qb->andWhere('a.start >= :end')
            ->orderBy('a.start', 'ASC')
            ->setParameters(array(
                'end' => $appointment ? $appointment->getEnd() : new \DateTime(),
            ));

        return $qb;
    }

    public function getNextAppointmentsByPatientQb(Appointment $appointment = null, Patient $patient)
    {
        $qb = $this->getNextAppointmentsQb($appointment);

        $qb->andWhere('a.patient = :patientId')
            ->setParameter('patientId', $patient->getId());

        return $qb;
    }

    public function isOverlapping(Event $event)
    {
        $qb = $this->getActiveEventsQb();
        $qb->andWhere('a.resource = :resource')
            ->andWhere('(a.end > :start AND a.start < :end)')
            ->andWhere('a.id != :id')
            ->setParameters(array(
                'resource' => $event->getResource(),
                'start' => $event->getStart(),
                'end' => $event->getEnd(),
                'id' => $event->getId(),
            ));

        $overlappingEvents = $qb->getQuery()->getResult();

        if (count($overlappingEvents) > 0) {
            return true;
        }

        return false;
    }


    protected function getRealEventClassName(Event $event)
    {
        return ClassUtils::getClass($event);
    }

    public function getRealEvent(Event $event)
    {
        return $this->entityManager->getRepository($this->getRealEventClassName($event))->find($event->getId());
    }

    public function getRealEventRoutePrefix(Event $event)
    {
        $realEventClassParts = explode('\\', $this->getRealEventClassName($event));
        $className = array_pop($realEventClassParts);
        return Inflector::tableize($className);
    }

    public function setEventDates(Event $event, $dateTime)
    {
        $dt = \DateTime::createFromFormat('Y-m-d\TH:i:s', $dateTime);
        $event->setStart($dt);
        $event->setEnd((clone $dt)->modify('+ ' . $this->getInterval() . 'minutes'));
    }
}
