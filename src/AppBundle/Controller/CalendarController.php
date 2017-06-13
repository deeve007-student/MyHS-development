<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 17:25
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventResource;
use AppBundle\Entity\Patient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Calendar controller.
 *
 * @Route("")
 */
class CalendarController extends Controller
{

    protected function getCalendarResponseData()
    {
        return array(
            'eventUtils' => $this->get('app.event_utils'),
            'resources' => json_encode(array_map(
                function (EventResource $resource) {
                    return $resource->getName();
                },
                $this->get('app.event_utils')->getResources()
            )),
        );
    }

    /**
     * @Route("/calendar", name="calendar_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return $this->getCalendarResponseData();
    }

    /**
     * @Route("/calendar/patient/{patient}", name="calendar_new_patient_event")
     * @Method("GET")
     * @Template("@App/Calendar/index.html.twig")
     */
    public function newPatientEventAction(Patient $patient)
    {
        $data = $this->getCalendarResponseData();
        $data['patient'] = $this->get('app.hasher')->encodeObject($patient);

        return $data;
    }
}
