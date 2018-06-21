<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:14
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\BulkPatientList;
use AppBundle\Entity\Message;
use AppBundle\Entity\NoShowMessage;
use AppBundle\Entity\Patient;
use AppBundle\Utils\AppNotificator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * NoShowMessageController controller.
 */
class NoShowMessageController extends Controller
{

    /**
     * Creates a new no show message entity
     *
     * @Route("/no-show-message/new/{appointment}", name="no_show_message_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function createAction(Appointment $appointment)
    {
        $noShowMessage = $this->get('app.entity_factory')->createNoShowMessage();
        $noShowMessage->setAppointment($appointment);
        return $this->createNewNoShowMessageResponse($noShowMessage);
    }

    /**
     * @param NoShowMessage $noShowMessage
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function createNewNoShowMessageResponse(NoShowMessage $noShowMessage)
    {
        $result = $this->update($noShowMessage);

        /** @var AppNotificator $notificator */
        $notificator = $this->get('app.notificator');

        $form = $this->get('app.no_show_message.form');
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($noShowMessage->getCommunicationType()->isByEmail()) {
                $message = new Message(Message::TYPE_EMAIL);
                $message->setRecipient($noShowMessage->getAppointment()->getPatient())
                    ->setSubject($noShowMessage->getSubject())
                    ->setBodyData($noShowMessage->getMessage());

                $notificator->sendMessage($message);
            }

            if ($noShowMessage->getCommunicationType()->isBySms()) {
                $message = new Message(Message::TYPE_SMS);
                $message->setRecipient($noShowMessage->getAppointment()->getPatient())
                    ->setSubject($noShowMessage->getSubject())
                    ->setBodyData($noShowMessage->getSms());

                $notificator->sendMessage($message);
            }

            $em->flush();
        }

        return $result;
    }

    /**
     * @param NoShowMessage $entity
     * @param array $additionalData
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function update(NoShowMessage $entity, $additionalData = array())
    {
        $form = $this->get('app.no_show_message.form');

        $template = '@App/NoShowMessage/include/form.html.twig';

        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $form,
            $template,
            $entity,
            'app.no_show_message.message.created',
            'app.no_show_message.message.updated',
            'message_log_index',
            null,
            null,
            $additionalData
        );
    }

}
