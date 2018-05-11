<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.09.2017
 * Time: 17:01
 */

namespace ReportBundle\Provider;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Patient;
use AppBundle\Utils\DateTimeUtils;
use AppBundle\Utils\EventUtils;
use ReportBundle\Entity\AppointmentsNode;
use Doctrine\ORM\QueryBuilder;
use ReportBundle\Entity\Node;
use ReportBundle\Entity\NullObject;
use ReportBundle\Entity\PatientsNode;
use ReportBundle\Form\Type\DateRangeType;
use Symfony\Component\VarDumper\VarDumper;

class PatientsFilterProvider extends AbstractReportProvider implements ReportProviderInterface
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
     * @return Node
     */
    public function getReportData($reportFormData)
    {
        $qb = $this->createQueryBuilder();
        $this->bindReportFormToQueryBuilder($qb, $reportFormData); // Первая фильтрация - на уровне запроса
        $data = $qb->getQuery()->getResult();
        $this->filterResults($data, $reportFormData); // Вторая фильтрация - по вычисляемым значениям

        $rootNode = new AppointmentsNode();

        $levels = $this->getNodeLevels($reportFormData);

        $this->processLevels($levels, $data, array(), $rootNode, $reportFormData);

        return $rootNode;
    }

    /**
     * @param $data
     * @param $reportFormData
     */
    protected function filterResults(&$data, $reportFormData)
    {

        if ($reportFormData['appointmentDateRange'] == 'range') {
            $appointmentDateStart = DateTimeUtils::getDate($reportFormData['appointmentDateStart'])->setTimezone(new \DateTimeZone('UTC'));
            $appointmentDateEnd = DateTimeUtils::getDate($reportFormData['appointmentDateEnd'])->setTime(23, 59, 59);
        } else {
            list($appointmentDateStart, $appointmentDateEnd) = DateRangeType::getRangeDates($reportFormData['appointmentDateRange']);
        }

        if ($reportFormData['recallDateRange'] == 'range') {
            $recallDateStart = DateTimeUtils::getDate($reportFormData['recallDateStart'])->setTimezone(new \DateTimeZone('UTC'));
            $recallDateEnd = DateTimeUtils::getDate($reportFormData['recallDateEnd'])->setTime(23, 59, 59);
        } else {
            list($recallDateStart, $recallDateEnd) = DateRangeType::getRangeDates($reportFormData['recallDateRange']);
        }

        if ($reportFormData['upcomingBirthdayDateRange'] == 'range') {
            $birthdayStart = DateTimeUtils::getDate($reportFormData['upcomingBirthdayDateStart'])->setTimezone(new \DateTimeZone('UTC'));
            $birthdayEnd = DateTimeUtils::getDate($reportFormData['upcomingBirthdayDateEnd'])->setTime(23, 59, 59);
        } else {
            list($birthdayStart, $birthdayEnd) = DateRangeType::getRangeDates($reportFormData['upcomingBirthdayDateRange']);
        }

        foreach ($data as $n => $value) {
            /** @var Patient $patient */
            $patient = $this->entityManager->getRepository('AppBundle:Patient')->find($value['patientId']);

            $unset = false;

            if (isset($reportFormData['referrer']) && $reportFormData['referrer']) {
                if ((string)$patient->getReferrer() !== (string)$reportFormData['referrer']) {
                    $unset = true;
                }
            }

            if (isset($reportFormData['noFutureAppointments']) && $reportFormData['noFutureAppointments']) {
                $patientFutureAppointments = $this->eventUtils->getActiveEventsQb(Appointment::class)
                    ->andWhere('a.start > :now')
                    ->andWhere('a.patient = :patient')
                    ->setParameter('patient', $patient)
                    ->setParameter('now', new \DateTime('now'))
                    ->getQuery()->getResult();

                if ($patientFutureAppointments) {
                    $unset = true;
                }
            }

            if (isset($reportFormData['upcomingAppointment']) && $reportFormData['upcomingAppointment'] == 'yes') {
                $qb = $this->eventUtils->getNextAppointmentsByPatientQb(null, $patient)
                    ->andWhere('a.start >= :dstart')
                    ->andWhere('a.end <= :dend')
                    ->setParameter('dstart',$appointmentDateStart)
                    ->setParameter('dend',$appointmentDateEnd);
                /** @var Appointment[] $nextAppointments */
                $nextAppointments = $qb->getQuery()->getResult();
                if (count($nextAppointments)==0) {
                    $unset = true;
                } else {
                    if (isset($reportFormData['treatmentModality']) && $reportFormData['treatmentModality']) {

                        $matchedApps = [];

                        foreach ($nextAppointments as $nextAppointment) {
                            if ($nextAppointment->getTreatment() == $reportFormData['treatmentModality']) {
                                $matchedApps[] = $nextAppointment;
                            }
                        }

                        if (count($matchedApps)==0) {
                            $unset = true;
                        }

                    }
                }
            }

            if (isset($reportFormData['upcomingAppointment']) && $reportFormData['upcomingAppointment'] == 'no') {
                $qb = $this->eventUtils->getNextAppointmentsByPatientQb(null, $patient)
                    ->setMaxResults(1);
                if ($nextAppointment = $qb->getQuery()->getOneOrNullResult()) {
                    $unset = true;
                }
            }

            if (isset($reportFormData['withRecall']) && $reportFormData['withRecall']) {
                $qb = $this->entityManager->getRepository('AppBundle:Recall')->createQueryBuilder('r');
                $qb->andWhere('r.patient = :patient')
                    ->setParameter('patient', $patient)
                    ->andWhere('r.date >= :dateStart')
                    ->andWhere('r.date <= :dateEnd');
                $qb->setParameter('dateStart', $recallDateStart);
                $qb->setParameter('dateEnd', $recallDateEnd);
                if (!$qb->getQuery()->getResult()) {
                    $unset = true;
                }
            }

            if (isset($reportFormData['upcomingBirthday']) && $reportFormData['upcomingBirthday']) {
                $bd = false;
                $now = new \DateTime();

                if ($patient->getDateOfBirth()) {
                    for ($year = $birthdayStart->format('Y'); $year <= $birthdayEnd->format('Y'); $year++) {
                        $dateToCheck = \DateTime::createFromFormat('Y-m-d', $year . '-' . $patient->getDateOfBirth()->format('m-d'));

                        if ($dateToCheck >= $birthdayStart && $dateToCheck <= $birthdayEnd && $dateToCheck >= $now) {
                            $bd = true;
                        }
                    }
                }

                if (!$bd) {
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
     */
    protected function processLevels($levels, $data, array $criteria, Node $node, $reportFormData)
    {
        $level = array_shift($levels);
        $levelData = $this->filter($data, $criteria, $level['field'], $level['class']);

        if (count($levels) == 0) {
            $this->processObjectData($node, $levelData['objects'], $level, $reportFormData);
        } else {
            /** @var Appointment $levelObject */
            foreach ($levelData['objects'] as $levelObject) {
                $levelNode = new AppointmentsNode();
                $levelNode->setObject($levelObject);

                $node->addChild($levelNode);

                if (isset($level['route']) && $level['route']) {
                    $levelNode->setRoute($this->router->generate($level['route'], array('id' => $levelObject->getId())));
                }

                $this->processLevels($levels, $levelData['data'], array($level['field'] => is_object($levelObject) ? $levelObject->getId() : $levelObject), $levelNode, $reportFormData);
            }
        }

    }

    /**
     * @param Node $node
     * @param Appointment[] $patients
     * @param array $level
     * @throws \Exception
     */
    protected function processObjectData(Node $node, array $patients, array $level, $reportFormData)
    {

        if ($reportFormData['recallDateRange'] == 'range') {
            $recallDateStart = DateTimeUtils::getDate($reportFormData['recallDateStart'])->setTimezone(new \DateTimeZone('UTC'));
            $recallDateEnd = DateTimeUtils::getDate($reportFormData['recallDateEnd'])->setTime(23, 59, 59);
        } else {
            list($recallDateStart, $recallDateEnd) = DateRangeType::getRangeDates($reportFormData['recallDateRange']);
        }

        if ($reportFormData['upcomingBirthdayDateRange'] == 'range') {
            $birthdayStart = DateTimeUtils::getDate($reportFormData['upcomingBirthdayDateStart'])->setTimezone(new \DateTimeZone('UTC'));
            $birthdayEnd = DateTimeUtils::getDate($reportFormData['upcomingBirthdayDateEnd'])->setTime(23, 59, 59);
        } else {
            list($birthdayStart, $birthdayEnd) = DateRangeType::getRangeDates($reportFormData['upcomingBirthdayDateRange']);
        }

        /** @var Patient $patient */
        foreach ($patients as $patient) {
            if ($patient->getDateOfBirth()) {

                $patientNode = new PatientsNode();

                $patientNode->setObject($patient);

                $bd = false;
                $now = new \DateTime();

                for ($year = $birthdayStart->format('Y'); $year <= $birthdayEnd->format('Y'); $year++) {
                    $dateToCheck = \DateTime::createFromFormat('Y-m-d', $year . '-' . $patient->getDateOfBirth()->format('m-d'));

                    if (!$bd && $dateToCheck >= $birthdayStart && $dateToCheck <= $birthdayEnd && $dateToCheck >= $now) {
                        $bd = true;
                        $diff = $patient->getDateOfBirth()->diff($dateToCheck);
                        $patientNode->setAge($diff->y);
                    }
                }

                $qb = $this->eventUtils->getNextAppointmentsByPatientQb(null, $patient)
                    ->setMaxResults(1);
                if ($nextAppointment = $qb->getQuery()->getOneOrNullResult()) {
                    $patientNode->setNextAppointment($nextAppointment);
                }

                $qb = $this->entityManager->getRepository('AppBundle:Recall')->createQueryBuilder('r');
                $qb->andWhere('r.patient = :patient')
                    ->setParameter('patient', $patient)
                    ->andWhere('r.date >= :dateStart')
                    ->andWhere('r.date <= :dateEnd');
                $qb->setParameter('dateStart', $recallDateStart);
                $qb->setParameter('dateEnd', $recallDateEnd);

                if ($recalls = $qb->getQuery()->getResult()) {
                    foreach ($recalls as $recall) {
                        $patientNode->addRecall($recall);
                    }
                }

                if (isset($level['route']) && $level['route']) {
                    $patientNode->setRoute($this->router->generate($level['route'], array('id' => $this->hasher->encodeObject($patient))));
                }

                $node->addChild($patientNode);
            }
        }
    }

    /**
     * @return QueryBuilder
     */
    protected function createQueryBuilder()
    {
        $qb = $this->entityManager->getRepository('AppBundle:Patient')->createQueryBuilder('patient')
            ->select('patient.id AS patientId');

        return $qb;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $reportFormData
     */
    protected function bindReportFormToQueryBuilder(QueryBuilder $queryBuilder, array $reportFormData)
    {
        /*
        if (isset($reportFormData['treatment']) && $reportFormData['treatment']) {
            $queryBuilder->andWhere('appointment.treatment = :treatment')
                ->setParameter('treatment', $reportFormData['treatment']);
        }
        */
    }

    /**
     * @param $reportFormData
     * @return array
     * @throws \Exception
     */
    protected function getNodeLevels($reportFormData)
    {

        $patientLevel = array(
            'field' => 'patient',
            'class' => Patient::class,
            'route' => 'patient_view',
        );

        /*
        $buLevel = array(
            'field' => 'businessUnit',
            'class' => BusinessUnit::class,
        );

        $erLevel = array(
            'field' => 'expenseRequest',
            'class' => ExpenseRequest::class,
            'route' => 'crm_expense_request_view',
            'acl' => 'crm_expense_request_view',
        );

        switch ($reportFormData['group']) {
            case 'businessUnit':
                return array($buLevel, $erLevel);
                break;
            case 'expenditure':
                return array($expenditureLevel, $erLevel);
                break;
        }
        */

        return array($patientLevel);

        //throw new \Exception('Undefined grouping: ' . $reportFormData['group']);
    }

}