<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:38
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Patient;
use AppBundle\Utils\FilterUtils;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * MessageLog controller.
 *
 * @Route("")
 */
class MessageLogController extends Controller
{

    /**
     * Lists all communications.
     *
     * @Route("/message-log/", name="message_log_index")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('AppBundle:MessageLog')->createQueryBuilder('l');
        $qb->leftJoin('l.patient', 'p')
            ->orderBy('l.date', 'DESC');

        return $this->filterMessageLogs($request, $qb);
    }

    /**
     * Lists all patients communications.
     *
     * @Route("/patient/{id}/message-log", name="patient_message_log_index")
     * @Method({"GET","POST"})
     * @Template("@App/MessageLog/indexPatient.html.twig")
     */
    public function indexPatientAction(Request $request, Patient $patient)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('AppBundle:MessageLog')->createQueryBuilder('l');
        $qb->leftJoin('l.patient', 'p')
            ->orderBy('l.date', 'DESC')
            ->where('l.patient = :patient')
            ->setParameter('patient', $patient);

        $result = $this->filterMessageLogs($request, $qb);

        if (is_array($result)) {
            $result['entity'] = $patient;
        }

        return $result;
    }

    protected function filterMessageLogs(Request $request, QueryBuilder $qb) {
        $result = $this->get('app.datagrid_utils')->handleDatagrid(
            $this->get('app.string_filter.form'),
            $request,
            $qb,
            function ($qb, $filterData) {
                FilterUtils::buildTextGreedyCondition(
                    $qb,
                    array(
                        'p.title',
                        'p.firstName',
                        'p.lastName',
                        'l.category',
                    ),
                    $filterData['string']
                );
            },
            '@App/MessageLog/include/grid.html.twig'
        );

        return $result;
    }
}
