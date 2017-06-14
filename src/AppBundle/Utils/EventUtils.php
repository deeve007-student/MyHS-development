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
use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\Translator;

class EventUtils
{
    /** @var  Hasher */
    protected $hasher;

    /** @var  Translator */
    protected $translator;

    /** @var  EntityManager */
    protected $entityManager;


    public function __construct(
        EntityManager $entityManager,
        Hasher $hasher,
        Translator $translator
    )
    {
        $this->hasher = $hasher;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
    }

    public function getInterval()
    {
        return '15';
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
            'tag' => null,
            'description' => $event->getDescription() ? $event->getDescription() : '',
            'start' => $event->getStart()->format(\DateTime::ATOM),
            'end' => $event->getEnd()->format(\DateTime::ATOM),
            'editable' => 1,
            'color' => '#D3D3D3',
            'textColor' => '#000',
            'column' => $this->getResourceNumber($event->getResource()),
            'birthday' => false
        );

        switch (get_class($event)) {
            case Appointment::class:
                /** @var Appointment $event */
                $eventData['tag'] = (string)$event->getTreatment();

                $now = new \DateTime();
                if ($event->getPatient()->getDateOfBirth()->format('md') == (new \DateTime())->format('md')) {
                    $eventData['birthday'] = true;
                }

                if ($color = $event->getTreatment()->getCalendarColour()) {
                    $eventData['color'] = $color;
                    $eventData['textColor'] = '#fff';
                }
                break;
            case UnavailableBlock::class:
                $eventData['tag'] = $this->translator->trans('app.unavailable_block.tag');
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

    public function getNextAppointmentsQb(Appointment $appointment = null)
    {
        $qb = $this->entityManager->getRepository('AppBundle:Appointment')->createQueryBuilder('a');

        $qb->where('a.start > :end')
            ->orderBy('a.start', 'ASC')
            ->setParameters(array(
                'end' => $appointment ? $appointment->getEnd() : new \DateTime(),
            ));

        return $qb;
    }

    public function getNextAppointmentsByPatientQb(Appointment $appointment = null, Patient $patient)
    {
        $qb = $this->getNextAppointmentsQb($appointment);

        $qb->andWhere('a.patient != :patientId')
            ->setParameter('patientId', $patient->getId());

        return $qb;
    }

    public function isOverlapping(Event $event)
    {
        $qb = $this->entityManager->getRepository('AppBundle:Event')->createQueryBuilder('e');
        $qb->where('e.resource = :resource')
            //->andWhere('(e.start < :start AND e.end > :start) OR (e.start < :end AND e.end > :end)')
            ->andWhere('(e.end > :start AND e.start < :end)')
            ->andWhere('e.id != :id')
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

}
