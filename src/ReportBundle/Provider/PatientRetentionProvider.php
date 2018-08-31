<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.09.2017
 * Time: 17:01
 */

namespace ReportBundle\Provider;

use AppBundle\Entity\Appointment;
use AppBundle\Utils\DateRangeUtils;
use AppBundle\Utils\DateTimeUtils;
use AppBundle\Utils\EventUtils;
use Doctrine\ORM\QueryBuilder;
use ReportBundle\Entity\Node;
use ReportBundle\Entity\PatientRetentionNode;
use ReportBundle\Form\Type\DateRangeType;
use Symfony\Component\VarDumper\VarDumper;

class PatientRetentionProvider extends AbstractReportProvider implements ReportProviderInterface
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
        $rootNode = new PatientRetentionNode();

        list($dateStart, $dateEnd) = DateRangeUtils::getYearDates(\DateTime::createFromFormat('Y-m-d', $reportFormData['year'] . '-01-01'));

        $monthes = DateRangeUtils::getMonthesArrayBetweenTwoDates($dateStart, $dateEnd);

        foreach ($monthes as $month) {
            $monthNode = new PatientRetentionNode();
            $this->calculateData($monthNode, $month['start'], $month['end'], $reportFormData['treatment']);
            $rootNode->addChild($monthNode);
        }

        return $rootNode;
    }

    protected function calculateData(PatientRetentionNode $node, $dateStart, $dateEnd, $treatment = null)
    {
        $patients = $this->entityManager->getRepository('AppBundle:Patient')->findAll();

        $checkTreatment = function (QueryBuilder $queryBuilder) use ($treatment) {
            if ($treatment) {
                $queryBuilder->andWhere('a.treatment = :treatment')
                    ->setParameter('treatment', $treatment);
            }
        };

        $node->setName($dateStart->format('F Y'));
        $node->setPatients(count($patients));

        foreach ($patients as $patient) {
            $firstAppQb = $this->eventUtils->getActiveEventsQb(Appointment::class)
                ->leftJoin('a.appointmentPatients', 'appointmentPatient')
                ->andWhere('appointmentPatient.patient = :patient')
                ->setParameter('patient', $patient)
                ->orderBy('a.start', 'ASC')
                ->setMaxResults(1);
            $checkTreatment($firstAppQb);
            $firstApp = $firstAppQb->getQuery()->getOneOrNullResult();

            $atThisMonthAppsQb = $this->eventUtils->getActiveEventsQb(Appointment::class)
                ->leftJoin('a.appointmentPatients', 'appointmentPatient')
                ->andWhere('appointmentPatient.patient = :patient')
                ->andWhere('a.end <= :end')
                ->setParameter('patient', $patient)
                ->setParameter('end', $dateEnd)
                ->orderBy('a.start', 'ASC');
            $checkTreatment($atThisMonthAppsQb);
            $atThisMonthApps = $atThisMonthAppsQb->getQuery()->getResult();

            if ($firstApp && $firstApp->getStart() >= $dateStart && $firstApp->getEnd() <= $dateEnd) {
                $node->setNew($node->getNew() + 1);
            }

            if (count($atThisMonthApps) == 5 && $atThisMonthApps[4] && $atThisMonthApps[4]->getStart() >= $dateStart && $atThisMonthApps[4]->getEnd() <= $dateEnd) {
                $node->setAtt5($node->getAtt5() + 1);
            }

            if (count($atThisMonthApps) == 10 && $atThisMonthApps[9] && $atThisMonthApps[9]->getStart() >= $dateStart && $atThisMonthApps[9]->getEnd() <= $dateEnd) {
                $node->setAtt10($node->getAtt10() + 1);
            }

            if (count($atThisMonthApps) == 20 && $atThisMonthApps[19] && $atThisMonthApps[19]->getStart() >= $dateStart && $atThisMonthApps[19]->getEnd() <= $dateEnd) {
                $node->setAtt20($node->getAtt20() + 1);
            }

            if (count($atThisMonthApps) == 50 && $atThisMonthApps[49] && $atThisMonthApps[49]->getStart() >= $dateStart && $atThisMonthApps[49]->getEnd() <= $dateEnd) {
                $node->setAtt50($node->getAtt50() + 1);
            }
        }
    }

}
