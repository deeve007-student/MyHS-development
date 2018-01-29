<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:38
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\Patient;
use AppBundle\Utils\FilterUtils;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
     * @Route("/communications/", name="message_log_index")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->getRepository('AppBundle:Message')->createQueryBuilder('l');
        $qb->leftJoin('l.patient', 'p')
            ->where('l.parentMessage IS NULL')
            ->orderBy('l.createdAt', 'DESC');

        return $this->filterMessageLogs($request, $qb);
    }

    /**
     * Lists all patients communications.
     *
     * @Route("/patient/{id}/communications", name="patient_message_log_index")
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
            ->setParameter('patient', $patient)
            ->orderBy('l.createdAt', 'DESC');

        $result = $this->filterMessageLogs($request, $qb);

        if (is_array($result)) {
            $result['entity'] = $patient;
        }

        return $result;
    }

    protected function filterMessageLogs(Request $request, QueryBuilder $qb)
    {
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
                        'l.tag',
                    ),
                    $filterData['string']
                );
            },
            null,
            '@App/MessageLog/include/grid.html.twig'
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