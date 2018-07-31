<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 06.06.2017
 * Time: 11:46
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\AppointmentPatient;
use AppBundle\Entity\Event;
use AppBundle\Entity\EventResource;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\Patient;
use AppBundle\Entity\PatientAlert;
use AppBundle\Entity\TreatmentNote;
use AppBundle\Entity\UnavailableBlock;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Translation\Translator;
use UserBundle\Entity\User;

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

    /** @var  Formatter */
    protected $formatter;

    /** @var  TokenStorage */
    protected $tokenStorage;

    /** @var  User */
    protected $user;

    public function __construct(
        EntityManager $entityManager,
        Hasher $hasher,
        Translator $translator,
        RequestStack $requestStack,
        Formatter $formatter,
        TokenStorage $tokenStorage
    )
    {
        $this->hasher = $hasher;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
        $this->formatter = $formatter;
        $this->tokenStorage = $tokenStorage;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function getInterval()
    {
        return $this->user->getCalendarSettings()->getTimeInterval();
    }

    public function getClassTranslation($object)
    {
        $namespace = explode('\\', get_class($object));
        return Inflector::tableize(array_pop($namespace));
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

    public function getWorkDayStart()
    {
        $dt = \DateTime::createFromFormat($this->formatter->getBackendTimeFormat(), $this->user->getCalendarSettings()->getWorkDayStart());
        return $dt->format('H:i');
    }

    /**
     * Return an array with work day time intervals (start and end)
     * @return array
     */
    public function getWorkDayIntervals($date = null)
    {
        if (!$date) {
            $date = new \DateTime();
        }

        $start = (clone $date)->setTime(0, 0, 0);
        $end = (clone $date)->setTime(23, 59, 59);

        $diff = $end->diff($start);

        $minutes = $diff->days * 24 * 60;
        $minutes += $diff->h * 60;
        $minutes += $diff->i;

        $intervalsCount = $minutes / $this->getInterval();

        $intervals = [];
        $tmp = clone $start;
        for ($n = 0; $n < $intervalsCount; $n++) {
            $intervals[] = array(
                'start' => clone $tmp,
                'end' => (clone $tmp)->modify('+' . $this->getInterval() . ' minutes'),
            );
            $tmp->modify('+' . $this->getInterval() . ' minutes');
        }

        return $intervals;
    }

    public function getWorkDayEnd()
    {
        $dt = \DateTime::createFromFormat($this->formatter->getBackendTimeFormat(), $this->user->getCalendarSettings()->getWorkDayEnd());
        return $dt->format('H:i');
    }

    public function calculateFontColor($hexColor)
    {
        $hexColor = preg_replace('/#+/', '', $hexColor);

        //////////// hexColor RGB
        $R1 = hexdec(substr($hexColor, 0, 2));
        $G1 = hexdec(substr($hexColor, 2, 2));
        $B1 = hexdec(substr($hexColor, 4, 2));

        //////////// Black RGB
        $blackColor = "#000000";
        $R2BlackColor = hexdec(substr($blackColor, 0, 2));
        $G2BlackColor = hexdec(substr($blackColor, 2, 2));
        $B2BlackColor = hexdec(substr($blackColor, 4, 2));

        //////////// Calc contrast ratio
        $L1 = 0.2126 * pow($R1 / 255, 2.2) +
            0.7152 * pow($G1 / 255, 2.2) +
            0.0722 * pow($B1 / 255, 2.2);

        $L2 = 0.2126 * pow($R2BlackColor / 255, 2.2) +
            0.7152 * pow($G2BlackColor / 255, 2.2) +
            0.0722 * pow($B2BlackColor / 255, 2.2);

        $contrastRatio = 0;
        if ($L1 > $L2) {
            $contrastRatio = (int)(($L1 + 0.05) / ($L2 + 0.05));
        } else {
            $contrastRatio = (int)(($L2 + 0.05) / ($L1 + 0.05));
        }

        //////////// If contrast is more than 5, return black color
        if ($contrastRatio > 5) {
            return '#000000';
        } else { //////////// if not, return white color.
            return '#ffffff';
        }
    }

    /**
     * @param $backgroundColor
     * @return string
     * @deprecated Old version, remove
     */
    public function calculateFontColorOld($backgroundColor)
    {
        $r = mb_substr($backgroundColor, 1, 2);
        $g = mb_substr($backgroundColor, 3, 2);
        $b = mb_substr($backgroundColor, 5, 2);

        if (hexdec($r) * 0.299 + hexdec($g) * 0.587 + hexdec($b) * 0.114 > 186) {
            return "#000000";
        }
        return "#ffffff";
    }

    public function serializeEvent(Event $event)
    {
        $eventData = array(
            'id' => $this->hasher->encodeObject($event, ClassUtils::getParentClass($event)) . ($event->isClone() ? '_clone' : ''),
            'class' => get_class($event),
            'title' => (string)$event,
            'description' => $event->getDescription() ? $event->getDescription() : '',
            'start' => $this->convertDateToUTC($event->getStart())->format(\DateTime::ATOM),
            'end' => $this->convertDateToUTC($event->getEnd())->format(\DateTime::ATOM),
            'editable' => 1,
            'column' => $this->getResourceNumber($event->getResource()),
            'birthday' => false,
            'unpaidInvoice' => false,
            'treatment' => false,
            'clone' => $event->isClone(),
            //'editable' => $event->isClone() ? false : true,
            'editable' => true,
            'isMirror' => $event->isMirror(),
            'entityLabelTranslation' => 'app.' . $this->getClassTranslation($event) . '.label',
            'entityTranslation' => $this->getClassTranslation($event),
        );

        switch (get_class($event)) {
            case Appointment::class:
                /** @var Appointment $event */

                $eventData['treatment'] = (string)$event->getTreatment();

                /** @var Patient[] $patients */
                $patients = array_map(function (AppointmentPatient $appointmentPatient) {
                    return $appointmentPatient->getPatient();
                }, $event->getAppointmentPatients()->toArray());

                $eventData['patientAlerts'] = array();
                /** @var PatientAlert $patientAlert */
                foreach ($patients as $patient) {
                    foreach ($patient->getAlerts() as $patientAlert) {
                        $eventData['patientAlerts'][] = $patientAlert->getText();
                    }
                }

                $eventData['tag'] = (string)$event->getTreatment();

                foreach ($patients as $patient) {
                    if ($patient->getDateOfBirth() && $patient->getDateOfBirth()->format('md') == $event->getStart()->format('md')) {
                        $eventData['birthday'] = true;
                    }
                }

                // If patient has at least one unpaid invoice - we will mark all the patient's appointments with red dot
                foreach ($patients as $patient) {
                    foreach ($patient->getInvoices() as $invoice) {
                        if ($invoice->getStatus() !== Invoice::STATUS_PAID && $invoice->getStatus() !== Invoice::STATUS_DRAFT) {
                            $eventData['unpaidInvoice'] = true;
                        }
                    }
                }

                $eventData['color'] = Appointment::DEFAULT_COLOR;

                if ($color = $event->getTreatment()->getCalendarColour()) {
                    $eventData['color'] = $color;
                }

                foreach ($event->getAppointmentPatients() as $appointmentPatient) {
                    if ($appointmentPatient->getNewPatient()) {
                        $eventData['color'] = Appointment::NEW_PATIENT_COLOR;
                    }
                }

                if ($event->getTreatmentNote() && $event->getTreatmentNote()->getStatus() == TreatmentNote::STATUS_FINAL) {
                    $eventData['color'] = Appointment::TREATMENT_NOTE_CREATED_COLOR;
                }

                $eventData['textColor'] = $this->calculateFontColor($eventData['color']);

                if ($class = $event->getLastEventClass()) {
                    $eventData['className'] = 'appointment-' . $class;
                }

                break;
            case UnavailableBlock::class:
                $eventData['color'] = '#c8c8c8';
                $eventData['textColor'] = $this->calculateFontColor($eventData['color']);
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
        return $this->entityManager->getRepository($class)->createQueryBuilder('a')
            ->orderBy('a.start', 'ASC');
    }

    public function getActiveEventsQb($class = Event::class)
    {
        $qb = $this->entityManager->getRepository($class)->createQueryBuilder('a');
        $qb->leftJoin(Appointment::class, 'app', 'WITH', 'a.id = app.id')
            ->where($qb->expr()->isNull('app.reason'));
        return $qb;
    }

    public function getCancelledEventsQb($class = Event::class)
    {
        $qb = $this->entityManager->getRepository($class)->createQueryBuilder('a');
        $qb->leftJoin(Appointment::class, 'app', 'WITH', 'a.id = app.id')
            ->where($qb->expr()->isNotNull('app.reason'));
        return $qb;
    }

    public function getResourceActiveEventsQb(EventResource $eventResource, $class = Event::class)
    {
        $qb = $this->getActiveEventsQb($class);
        $qb->andWhere('a.resource = :resource')
            ->setParameter('resource', $eventResource);
        return $qb;
    }

    public function getPrevAppointmentsQb(Appointment $appointment = null)
    {
        $qb = $this->getActiveEventsQb(Appointment::class);

        $qb->andWhere('a.end <= :start')
            ->orderBy('a.end', 'DESC')
            ->setParameters(array(
                'start' => $appointment ? $appointment->getStart() : new \DateTime(),
            ));

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

        $qb->leftJoin('a.appointmentPatients', 'appointmentPatient')
            ->andWhere('appointmentPatient.patient = :patientId')
            ->setParameter('patientId', $patient->getId());

        return $qb;
    }

    public function getPrevAppointmentsByPatientQb(Appointment $appointment = null, Patient $patient)
    {
        $qb = $this->getPrevAppointmentsQb($appointment);

        $qb->leftJoin('a.appointmentPatients', 'appointmentPatient')
            ->andWhere('appointmentPatient.patient = :patientId')
            ->setParameter('patientId', $patient->getId());

        return $qb;
    }

    /**
     * @param array|Event[] $events
     */
    public function processMirrors(array &$events)
    {
        if (count($events)) {
            $unavailableBlocks = [];

            foreach ($events as $event) {
                if ($event instanceof UnavailableBlock && $event->isMirror()) {
                    $eventResource = $event->getResource();
                    $resources = clone $this->user->getCalendarSettings()->getResources();
                    $resources->removeElement($eventResource);
                    foreach ($resources as $resource) {
                        $eventCopy = clone $event;
                        $eventCopy->setId($event->getId());
                        $eventCopy->setResource($resource);
                        $eventCopy->setIsClone(true);
                        $unavailableBlocks[] = $eventCopy;
                    }
                }
            }

            if (count($unavailableBlocks)) {
                $events = array_merge($events, $unavailableBlocks);
            }
        }
    }

    public function isOverlapping(Event $eventToCheck)
    {
        $eventsToCheck = array($eventToCheck);
        if ($eventToCheck instanceof UnavailableBlock) {
            $this->processMirrors($eventsToCheck);
        }

        foreach ($eventsToCheck as $event) {

            $overlappingEvents = array();
            $events = $this->getActiveEventsQb()->getQuery()->getResult();
            $this->processMirrors($events);

            /** @var Event $e */
            foreach ($events as $e) {
                if ($event->getId() !== $e->getId()) {
                    if (
                        $e->getResource()->getId() == $event->getResource()->getId() &&
                        $e->getStart() < $event->getEnd() &&
                        $e->getEnd() > $event->getStart()
                    ) {
                        $overlappingEvents[] = $e;
                    }
                }
            }

            if (count($overlappingEvents) > 0) {
                return true;
            }

        }

        return false;
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     * @param EventResource|null $resource
     * @return Event[]Event|null
     * @throws \Exception
     */
    public function getIntervalAppointments(\DateTime $start, \DateTime $end, EventResource $resource = null)
    {
        $qb = $this->getActiveEventsQb(Appointment::class);
        $qb->andWhere('(a.end > :start AND a.start < :end)')
            ->setParameters(array(
                'start' => $start,
                'end' => $end,
            ));

        if ($resource) {
            $qb->andWhere('a.resource = :resource')
                ->setParameter('resource', $resource);
        }

        $events = $qb->getQuery()->getResult();

        if ($resource) {
            if (count($events) > 1) {
                throw new \Exception('Only one event can exist per one resource at any time point');
            }
            if (count($events) == 0) {
                return null;
            }
            return $events[0];
        }

        return $events;
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
        $dt = $this->parseDateFromUTC($dateTime);
        $event->setStart($dt);
        $event->setEnd((clone $dt)->modify('+ ' . $this->getInterval() . 'minutes'));
    }

    public function parseDateFromUTC($dateTime)
    {
        return \DateTime::createFromFormat('Y-m-d\TH:i:s', mb_substr($dateTime, 0, 19), new \DateTimeZone($this->user->getTimezone()));
    }

    public function convertDateToUTC(\DateTime $dateTime)
    {
        $offsetSeconds = (new \DateTimeZone($this->user->getTimezone()))->getOffset($dateTime);
        if ($offsetSeconds !== 0) {
            $offsetSecondsStr = $offsetSeconds > 0 ? '+' . abs($offsetSeconds) . 'seconds' : '-' . abs($offsetSeconds) . 'seconds';
            return (clone $dateTime)->modify($offsetSecondsStr);
        }
        return $dateTime;
    }

}
