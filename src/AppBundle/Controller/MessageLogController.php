<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:38
 */

namespace AppBundle\Controller;

use AppBundle\Entity\BulkPatientList;
use AppBundle\Entity\CommunicationEvent;
use AppBundle\Entity\ManualCommunication;
use AppBundle\Entity\Message;
use AppBundle\Entity\Patient;
use AppBundle\Utils\FilterUtils;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\VarDumper\VarDumper;

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
     * @Route("/communications/list/{bulkPatientList}", defaults={"bulkPatientList"=null}, name="message_log_index", options={"expose"=true})
     * @Method({"GET","POST"})
     * @Template()
     */
    public function indexAction(Request $request, $bulkPatientList)
    {
        if ($bulkPatientList) {
            $bulkPatientListId = $this->get('app.hasher')->decode($bulkPatientList, BulkPatientList::class);
            $bulkPatientList = $this->getDoctrine()->getRepository('AppBundle:BulkPatientList')->find($bulkPatientListId);
        }

        $em = $this->getDoctrine()->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->getRepository('AppBundle:Message')->createQueryBuilder('l');
        $qb->leftJoin('l.patient', 'p')
            ->where('l.parentMessage IS NULL')
            ->andWhere('l.manualCommunication IS NULL')
            ->orderBy('l.createdAt', 'DESC');

        /** @var QueryBuilder $qb */
        $qbManual = $em->getRepository('AppBundle:Message')->createQueryBuilder('l');
        $qbManual->leftJoin('l.patient', 'p')
            ->leftJoin('l.manualCommunication', 'mc')
            ->where('l.parentMessage IS NULL')
            ->andWhere('l.manualCommunication IS NOT NULL')
            ->orderBy('l.createdAt', 'DESC')
            ->groupBy('mc.id');

        $qbr = $em->getRepository('AppBundle:CommunicationEvent')->createQueryBuilder('r');
        $qbr->leftJoin('r.patient', 'p')
            ->orderBy('r.date', 'DESC');

        $result = $this->filterMessageLogs($request, array($qb, $qbManual, $qbr));

        if (is_array($result) && $bulkPatientList) {
            $result['bulkPatientList'] = $this->get('app.hasher')->encodeObject($bulkPatientList);
        }

        return $result;
    }

    /**
     * Lists all patients communications.
     *
     * @Route("/patient/{id}/communications", name="patient_message_log_index", options={"expose"=true})
     * @Method({"GET","POST"})
     * @Template("@App/MessageLog/indexPatient.html.twig")
     */
    public function indexPatientAction(Request $request, Patient $patient)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('AppBundle:Message')->createQueryBuilder('l');
        $qb->leftJoin('l.patient', 'p')
            ->where('l.patient = :patient')
            ->andWhere('l.parentMessage IS NULL')
            ->andWhere('l.manualCommunication IS NULL')
            ->setParameter('patient', $patient)
            ->orderBy('l.createdAt', 'DESC');

        /** @var QueryBuilder $qb */
        $qbManual = $em->getRepository('AppBundle:Message')->createQueryBuilder('l');
        $qbManual->leftJoin('l.patient', 'p')
            ->leftJoin('l.manualCommunication', 'mc')
            ->where('l.parentMessage IS NULL')
            ->andWhere('l.manualCommunication IS NOT NULL')
            ->orderBy('l.createdAt', 'DESC')
            ->groupBy('mc.id');

        $qbr = $em->getRepository('AppBundle:CommunicationEvent')->createQueryBuilder('r');
        $qbr->leftJoin('r.patient', 'p')
            ->where('r.patient = :patient')
            ->setParameter('patient', $patient)
            ->orderBy('r.date', 'DESC');

        $result = $this->filterMessageLogs($request, array($qb, $qbManual, $qbr));

        if (is_array($result)) {
            $result['entity'] = $patient;
        }

        return $result;
    }

    /**
     * @param Request $request
     * @param QueryBuilder|array $qb
     * @return array|Response
     */
    protected function filterMessageLogs(Request $request, $qb)
    {
        $result = $this->get('app.datagrid_utils')->handleDatagrid(
            $this->get('app.string_filter.form'),
            $request,
            $qb,
            function ($qb, $filterData) {

                $messageLogFilter = function (&$qb, $filterData) {
                    FilterUtils::buildTextGreedyCondition(
                        $qb,
                        array(
                            'p.title',
                            'p.firstName',
                            'p.lastName',
                            'l.tag',
                        ),
                        $filterData['string']
                    );
                };

                $manualCommunicationFilter = function (&$qb, $filterData) {
                    FilterUtils::buildTextGreedyCondition(
                        $qb,
                        array(
                            'p.title',
                            'p.firstName',
                            'p.lastName',
                            'l.tag',
                        ),
                        $filterData['string']
                    );
                };

                $communicationEventFilter = function (&$qb, $filterData) {
                    FilterUtils::buildTextGreedyCondition(
                        $qb,
                        array(
                            'p.title',
                            'p.firstName',
                            'p.lastName',
                            'r.description',
                        ),
                        $filterData['string']
                    );
                };

                if (is_array($qb)) {
                    /** @var QueryBuilder $builder */
                    foreach ($qb as $builder) {

                        if ($builder->getRootEntities()[0] == Message::class) {
                            $messageLogFilter($builder, $filterData);
                        }

                        if ($builder->getRootEntities()[0] == ManualCommunication::class) {
                            $manualCommunicationFilter($builder, $filterData);
                        }

                        if ($builder->getRootEntities()[0] == CommunicationEvent::class) {
                            $communicationEventFilter($builder, $filterData);
                        }

                        if ($builder->getRootEntities()[0] == ManualCommunication::class) {
                            $communicationEventFilter($builder, $filterData);
                        }
                    }
                } else {
                    $messageLogFilter($qb, $filterData);
                }

            },
            '@App/MessageLog/include/grid.html.twig',
            null,
            function (&$resultArray) {
                usort($resultArray, function ($a, $b) {
                    if ($a instanceof Message) {
                        $ad = $a->getCreatedAt();
                    }
                    if ($b instanceof Message) {
                        $bd = $b->getCreatedAt();
                    }
                    if ($a instanceof CommunicationEvent) {
                        $ad = $a->getDate();
                    }
                    if ($b instanceof CommunicationEvent) {
                        $bd = $b->getDate();
                    }
                    if ($a instanceof ManualCommunication) {
                        $ad = $a->getCreatedAt();
                    }
                    if ($b instanceof ManualCommunication) {
                        $bd = $b->getCreatedAt();
                    }
                    return $ad > $bd ? -1 : 1;
                });
            }
        );

        return $result;
    }

    /**
     * Register an SMS reply.
     *
     * @Route("/sms-reply/{apiKey}", name="message_log_sms_reply")
     * @Method({"POST"})
     */
    public function smsReplyAction(Request $request, $apiKey)
    {
        $phoneUtils = $this->get('app.phone_utils');

        if ($apiKey == $this->getParameter('api_key_global')) {

            $xml = new \SimpleXMLElement('<Response/>');

            $patientNumber = $request->request->get('From');
            $userNumber = $request->request->get('To');
            $body = $request->request->get('Body');
            $sid = $request->request->get('SmsMessageSid');

            if ($fromPatient = $phoneUtils->getPatientByPhoneNumber($patientNumber)) {

                /** @var QueryBuilder $lastMessageQb */
                $lastMessageQb = $this->getDoctrine()->getManager()->getRepository('AppBundle:Message')->createQueryBuilder('m');

                /** @var Message $lastMessage */
                if ($lastMessage = $lastMessageQb
                    ->where('m.patient = :patient')
                    ->andWhere('m.parentMessage IS NULL')
                    ->andWhere('m.type = :sms')
                    ->setParameters(array(
                        'patient' => $fromPatient,
                        'sms' => Message::TYPE_SMS,
                    ))
                    ->orderBy('m.createdAt', 'DESC')
                    ->setMaxResults(1)
                    ->getQuery()->getOneOrNullResult()) {
                    $message = new Message();
                    $message->setSid($sid)
                        ->setBodyData($body)
                        ->setParentMessage($lastMessage)
                        ->setRecipient($lastMessage->getPatient())
                        ->setOwner($lastMessage->getOwner());

                    $message->compile($this->get('twig'), $this->get('app.formatter'));
                    $this->getDoctrine()->getManager()->persist($message);
                    $this->getDoctrine()->getManager()->flush();

                    $xml->addChild('Sms', 'Thanks for the message. Your doctor will read it soon.');
                } else {
                    $xml->addChild('Sms', 'Thanks for the message. But we can\'t understand your message subject. Please call your doctor.');
                }
            } else {
                $xml->addChild('Sms', 'Thanks for messaging us but you are not registered as patient.');
            }

            $response = new Response();
            $response->headers->set('Content-Type', 'text/xml');
            $response->setContent(str_replace("<?xml version=\"1.0\"?>\n", '', $xml->saveXML()));

            return $response;
        }

        throw new AccessDeniedHttpException();
    }
}
