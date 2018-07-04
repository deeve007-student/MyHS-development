<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.09.2017
 * Time: 17:01
 */

namespace ReportBundle\Provider;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\AppointmentPatient;
use AppBundle\Entity\Reschedule;
use AppBundle\Utils\DateTimeUtils;
use AppBundle\Utils\EventUtils;
use ReportBundle\Entity\AppointmentsNode;
use Doctrine\ORM\QueryBuilder;
use ReportBundle\Entity\Node;
use ReportBundle\Entity\NullObject;
use ReportBundle\Form\Type\DateRangeType;
use Symfony\Component\VarDumper\VarDumper;

class AppointmentsProvider extends AbstractReportProvider implements ReportProviderInterface
{

    /** @var  string */
    protected $nodeValueClass;

    /** @var  EventUtils */
    protected $eventUtils;

    public function setEventUtils(EventUtils $eventUtils)
    {
        $this->eventUtils = $eventUtils;
    }

    /**
     * @param $reportFormData
     * @return AppointmentsNode|\ReportBundle\Provider\Node
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getReportData($reportFormData)
    {
        $qb = $this->createQueryBuilder();
        $this->bindReportFormToQueryBuilder($qb, $reportFormData); // Первая фильтрация - на уровне запроса
        $data = $qb->getQuery()->getResult();
        $this->filterResults($data, $reportFormData); // Вторая фильтрация - по вычисляемым значениям

        $rootNode = new AppointmentsNode();

        $levels = $this->getNodeLevels($reportFormData);

        $this->processLevels($levels, $data, array(), $rootNode);

        return $rootNode;
    }

    /**
     * @param $data
     * @param $reportFormData
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function filterResults(&$data, $reportFormData)
    {

        if ($reportFormData['range'] == 'range') {
            $start = DateTimeUtils::getDate($reportFormData['dateStart'])->setTimezone(new \DateTimeZone('UTC'));
            $end = DateTimeUtils::getDate($reportFormData['dateEnd'])->setTime(23, 59, 59);
        } else {
            list($start, $end) = DateRangeType::getRangeDates($reportFormData['range']);
        }

        foreach ($data as $n => $value) {
            /** @var AppointmentPatient $appointmentPatient */
            $appointmentPatient = $this->entityManager->getRepository('AppBundle:AppointmentPatient')->find($value['appointmentPatientId']);

            $unset = false;

            if ($appointmentPatient->getAppointment()->getStart() < $start || $appointmentPatient->getAppointment()->getEnd() > $end) {
                $unset = true;
            } else {

                if (isset($reportFormData['firstAppointment']) && $reportFormData['firstAppointment']) {

                    $patientFirstAppointmentQb = $this->eventUtils->getActiveEventsQb(Appointment::class)
                        ->leftJoin('a.appointmentPatients', 'appointmentPatient');
                    $patientFirstAppointmentQb->andWhere('appointmentPatient.patient = :patient')
                        ->setParameter('patient', $appointmentPatient->getPatient())
                        ->orderBy('a.start', 'ASC')
                        ->setMaxResults(1);

                    if ($firstAppointment = $patientFirstAppointmentQb->getQuery()->getOneOrNullResult()) {
                        if ($firstAppointment !== $appointmentPatient->getAppointment()) {
                            $unset = true;
                        }
                    }

                }

                if (isset($reportFormData['noFutureAppointments']) && $reportFormData['noFutureAppointments']) {

                    $patientFutureAppointments = $this->eventUtils->getActiveEventsQb(Appointment::class)
                        ->leftJoin('a.appointmentPatients', 'appointmentPatient')
                        ->andWhere('a.start > :now')
                        ->andWhere('appointmentPatient.patient = :patient')
                        ->setParameter('patient', $appointmentPatient->getPatient())
                        ->setParameter('now', new \DateTime('now'))
                        ->getQuery()->getResult();

                    if ($patientFutureAppointments) {
                        $unset = true;
                    }

                }
            }

            if (isset($reportFormData['changedCancelled']) && $reportFormData['changedCancelled']) {
                $cancelReason = $appointmentPatient->getAppointment()->getReason();
                $reschedules = $appointmentPatient->getAppointment()->getReschedules();
                if (!count($reschedules) && !$cancelReason) {
                    $unset = true;
                }
            }

            if ($unset) {
                unset($data[$n]);
            }

        }
    }

    /**
     * @param array $data
     * @param array $criteria
     * @param string $field
     * @param string $class
     * @return array
     */
    protected function filter(array $data, array $criteria, $field, $class)
    {
        foreach ($criteria as $criteriaField => $criteriaValue) {
            $data = array_filter($data, function ($row) use ($criteriaField, $criteriaValue) {
                return $row[$criteriaField . 'Id'] == $criteriaValue ? true : false;
            });
        }

        $objects = array_map(function ($id) use ($class) {
            if ($id) {
                $object = $this->entityManager->getRepository($class)->findOneBy(array('id' => $id));
            } else {
                $object = new NullObject();
            }
            return $object;
        }, array_values(array_unique(array_map(function ($row) use ($field) {
            return $row[$field . 'Id'];
        }, $data))));

        $this->sortObjects($objects);

        return array(
            'data' => $data,
            'objects' => $objects,
        );
    }

    /**
     * @param $levels
     * @param $data
     * @param array $criteria
     * @param Node $node
     * @throws \Exception
     */
    protected function processLevels($levels, $data, array $criteria, Node $node)
    {
        $level = array_shift($levels);
        $levelData = $this->filter($data, $criteria, $level['field'], $level['class']);

        if (count($levels) == 0) {
            $this->processObjectData($node, $levelData['objects'], $level);
        } else {
            /** @var Appointment $levelObject */
            foreach ($levelData['objects'] as $levelObject) {
                $levelNode = new AppointmentsNode();
                $levelNode->setObject($levelObject);

                $node->addChild($levelNode);

                if (isset($level['route']) && $level['route']) {
                    $levelNode->setRoute($this->router->generate($level['route'], array('id' => $levelObject->getId())));
                }

                $this->processLevels($levels, $levelData['data'], array($level['field'] => is_object($levelObject) ? $levelObject->getId() : $levelObject), $levelNode);
            }
        }

    }

    /**
     * @param Node $node
     * @param AppointmentPatient[] $appointmentPatients
     * @param array $level
     * @throws \Exception
     */
    protected function processObjectData(Node $node, array $appointmentPatients, array $level)
    {
        foreach ($appointmentPatients as $appointmentPatient) {

            $appointmentNode = new AppointmentsNode();

            $appointmentNode->setObject($appointmentPatient);
            $appointmentNode->setName($appointmentPatient->getPatient());

            $reschedules = $appointmentPatient->getAppointment()->getReschedules();
            if (isset($reschedules[0])) {
                /** @var Reschedule $reschedule */
                $reschedule = $reschedules[0];
                $appointmentNode->setType('Rescheduled');
                $appointmentNode->setReason('');
                $appointmentNode->setOriginalStart($reschedule->getStart());
            }

            $cancelReason = $appointmentPatient->getAppointment()->getReason();
            if ($cancelReason) {
                $appointmentNode->setType('Canceled');
                $appointmentNode->setReason($cancelReason->getName());
                $appointmentNode->setOriginalStart(null);
            }

            if (isset($level['route']) && $level['route']) {
                $appointmentNode->setRoute($this->router->generate($level['route'], array('id' => $appointmentPatient->getAppointment()->getId())));
            }

            $node->addChild($appointmentNode);
        }

    }

    /**
     * @return QueryBuilder
     */
    protected
    function createQueryBuilder()
    {
        $qb = $this->entityManager->getRepository('AppBundle:AppointmentPatient')->createQueryBuilder('appointmentPatient')
            ->select('appointmentPatient.id AS appointmentPatientId, appointment.id AS appointmentId, patient.id as patientId, treatment.id as treatmentId')
            ->leftJoin('appointmentPatient.appointment', 'appointment')
            ->leftJoin('appointmentPatient.patient', 'patient')
            ->leftJoin('appointment.treatment', 'treatment')
            ->orderBy('appointment.start', 'DESC');

        return $qb;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $reportFormData
     */
    protected
    function bindReportFormToQueryBuilder(QueryBuilder $queryBuilder, array $reportFormData)
    {
        if (isset($reportFormData['treatment']) && $reportFormData['treatment']) {
            $queryBuilder->andWhere('appointment.treatment = :treatment')
                ->setParameter('treatment', $reportFormData['treatment']);
        }
    }

    /**
     * @param $reportFormData
     * @return array
     * @throws \Exception
     */
    protected
    function getNodeLevels($reportFormData)
    {

        $appointmentLevel = array(
            'field' => 'appointmentPatient',
            'class' => AppointmentPatient::class,
            'route' => 'appointment_view',
        );

        return array($appointmentLevel);
    }

}
