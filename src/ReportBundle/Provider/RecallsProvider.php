<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.09.2017
 * Time: 17:01
 */

namespace ReportBundle\Provider;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Recall;
use AppBundle\Utils\DateTimeUtils;
use AppBundle\Utils\EventUtils;
use AppBundle\Utils\RecallUtils;
use ReportBundle\Entity\Node;
use ReportBundle\Form\Type\DateRangeType;

class RecallsProvider extends AbstractReportProvider implements ReportProviderInterface
{

    /** @var  string */
    protected $nodeValueClass;

    /** @var  RecallUtils */
    protected $recallUtils;

    /** @var  EventUtils */
    protected $eventUtils;

    public function setRecallUtils(RecallUtils $recallUtils)
    {
        $this->recallUtils = $recallUtils;
    }

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
        if ($reportFormData['range'] == 'range') {
            $start = DateTimeUtils::getDate($reportFormData['dateStart'])->setTimezone(new \DateTimeZone('UTC'));
            $end = DateTimeUtils::getDate($reportFormData['dateEnd'])->setTime(23, 59, 59);
        } else {
            list($start, $end) = DateRangeType::getRangeDates($reportFormData['range']);
        }

        $rootNode = new Node();

        /** @var Recall $recall */
        foreach ($this->recallUtils->getAllRecallsQb()->getQuery()->getResult() as $recall) {


            if ($recall->getDate() < new \DateTime()) {
                $status = 'past';
            } else {
                $status = 'current';
            }

            if ($reportFormData['status'] == $status || $reportFormData['status'] == 'all') {
                if ((in_array($reportFormData['status'], array('all', 'past'))
                        && $recall->getDate() >= $start
                        && $recall->getDate() <= $end
                    ) || $reportFormData['status'] == 'current') {

                    $node = new Node();
                    $node->setObject($recall);
                    $rootNode->addChild($node);
                    $node->status = $status;

                    $node->treatment = null;
                    $qb = $this->eventUtils->getPrevAppointmentsByPatientQb(null, $recall->getPatient());
                    if ($apps = $qb->getQuery()->getResult()) {
                        $node->treatment = $apps[0]->getTreatment();
                    }

                }
            }
        }

        return $rootNode;
    }

}
