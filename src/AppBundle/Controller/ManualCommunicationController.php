<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:14
 */

namespace AppBundle\Controller;

use AppBundle\Entity\BulkPatientList;
use AppBundle\Entity\Message;
use AppBundle\Entity\ManualCommunication;
use AppBundle\Entity\Patient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * ManualCommunication controller.
 */
class ManualCommunicationController extends Controller
{

    /**
     * Creates a new manual communication entity.
     *
     * @Route("/communications/bulk-patients-list/{list}", name="bulk_patients_list_view", options={"expose"=true})
     * @Method({"GET"})
     * @Template("@App/ManualCommunication/patientsListView.html.twig")
     */
    public function patientsListViewAction(BulkPatientList $list)
    {
        return array(
            'list' => $list,
        );
    }

    /**
     * Creates a new manual communication entity.
     *
     * @Route("/manual-communication/new/{bulkPatientList}", defaults={"bulkPatientList"=null}, name="manual_communication_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function createAction($bulkPatientList = null)
    {
        $manualCommunication = $this->get('app.entity_factory')->createManualCommunication();

        if ($bulkPatientList) {
            $bulkPatientListId = $this->get('app.hasher')->decode($bulkPatientList, BulkPatientList::class);
            $bulkPatientList = $this->getDoctrine()->getRepository('AppBundle:BulkPatientList')->find($bulkPatientListId);
            $manualCommunication->setBulkPatientList($bulkPatientList);
        }

        return $this->createNewManualCommunicationResponse($manualCommunication);
    }

    /**
     * Creates a new manual communication entity from patient screen
     *
     * @Route("/patient/{patient}/manual-communication/new", name="manual_communication_create_patient", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function createPatientAction($patient = null)
    {
        $manualCommunication = $this->get('app.entity_factory')->createManualCommunication();

        if ($patient) {
            $patientId = $this->get('app.hasher')->decode($patient, Patient::class);
            $patient = $this->getDoctrine()->getRepository('AppBundle:Patient')->find($patientId);
            $manualCommunication->setPatient($patient);
        }

        return $this->createNewManualCommunicationResponse($manualCommunication, true);
    }

    protected function createNewManualCommunicationResponse(ManualCommunication $manualCommunication, $patient = false)
    {
        if ($patient) {
            $result = $this->updatePatient($manualCommunication);
        } else {
            $result = $this->update($manualCommunication);
        }

        $form = $this->get('app.manual_communication.form');
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $notificator = $this->get('app.notificator');

            $addressees = array();

            $messageType = null;
            if ($manualCommunication->getBulkPatientList()) {
                $messageType = Message::TAG_BULK_COMMUNICATION;
                foreach ($manualCommunication->getBulkPatientList()->getPatients() as $patient) {
                    $addressees[] = $patient;
                }
            } else {
                $messageType = Message::TAG_MANUAL_COMMUNICATION;
                $addressees[] = $manualCommunication->getPatient();
            }

            foreach ($addressees as $addressee) {
                if ($manualCommunication->getCommunicationType()->isByEmail()) {
                    $message = new Message(Message::TYPE_EMAIL);
                    $message->setTag($messageType)
                        ->setRecipient($addressee)
                        ->setSubject($manualCommunication->getSubject())
                        ->setBodyData($manualCommunication->getMessage())
                        ->setManualCommunication($manualCommunication);

                    if ($manualCommunication->getFile()) {
                        $message->addAttachment($manualCommunication->getFile()->getRealPath());
                    }

                    $notificator->sendMessage($message);
                }

                if ($manualCommunication->getCommunicationType()->isBySms()) {
                    $message = new Message(Message::TYPE_SMS);
                    $message->setTag($messageType)
                        ->setRecipient($addressee)
                        ->setSubject($manualCommunication->getSubject())
                        ->setBodyData($manualCommunication->getSms())
                        ->setManualCommunication($manualCommunication);

                    $notificator->sendMessage($message);
                }
            }

            $em->flush();
        }

        return $result;
    }


    /**
     * Download document.
     *
     * @Route("/communications{id}/download", name="communication_attachment_download")
     * @Method("GET")
     */
    public function downloadAction(ManualCommunication $attachment)
    {
        $downloadHandler = $this->get('vich_uploader.download_handler');

        return $downloadHandler->downloadObject($attachment, 'file', null, $attachment->getFileName());
    }

    /**
     * Creates patients list for bulk communication.
     *
     * @Route("/communications/create-bulk-patients-list/{list}/{filters}", name="bulk_patients_list_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function bulkPatientsListAction($list, $filters)
    {
        $em = $this->getDoctrine()->getManager();

        $patientIds = explode(',', $list);

        $bulkList = new BulkPatientList();
        $bulkList->setFilters($filters);

        foreach ($patientIds as $patientId) {
            $patient = $em->getRepository('AppBundle:Patient')->find($patientId);
            $bulkList->addPatient($patient);
        }

        if ($bulkList->getPatients()->count() > 0) {
            $em->persist($bulkList);
            $em->flush();
        }

        return $this->redirectToRoute('message_log_index', array('bulkPatientList' => $this->get('app.hasher')->encodeObject($bulkList, BulkPatientList::class)));
    }

    /**
     * Displays a form to edit an existing manual communication entity.
     *
     * @Route("/manual-communication/{id}/update", name="manual_communication_update", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function updateAction(ManualCommunication $manualCommunication)
    {
        return $this->update($manualCommunication, array(
            'bulk' => $manualCommunication->getBulkPatientList() ? true : false,
            'type' => $manualCommunication->getCommunicationType()->getName(),
        ));
    }

    protected function update(ManualCommunication $entity, $additionalData = array())
    {
        $form = $this->get('app.manual_communication.form');

        $template = $entity->getBulkPatientList() ? '@App/ManualCommunication/include/formBulk.html.twig' : '@App/ManualCommunication/include/form.html.twig';

        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $form,
            $template,
            $entity,
            'app.manual_communication.message.created',
            'app.manual_communication.message.updated',
            'message_log_index',
            null,
            null,
            $additionalData
        );
    }

    protected function updatePatient(ManualCommunication $entity, $additionalData = array())
    {
        $form = $this->get('app.manual_communication.form');

        $template = '@App/ManualCommunication/include/formPatient.html.twig';

        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $form,
            $template,
            $entity,
            'app.manual_communication.message.created',
            'app.manual_communication.message.updated',
            'message_log_index',
            null,
            null,
            $additionalData
        );
    }

}
