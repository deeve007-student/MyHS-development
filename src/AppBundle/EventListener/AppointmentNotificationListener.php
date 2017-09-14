<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 18:23
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Message;
use AppBundle\Event\AppointmentEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use AppBundle\Utils\AppNotificator;
use AppBundle\Utils\Formatter;
use AppBundle\Utils\Hasher;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\Translator;

class AppointmentNotificationListener
{

    use RecomputeChangesTrait;

    /** @var Hasher */
    protected $hasher;

    /** @var AppNotificator */
    protected $appNotificator;

    /** @var Translator */
    protected $translator;

    /** @var Formatter */
    protected $formatter;

    /** @var \Twig_Environment */
    protected $twig;

    public function __construct(Hasher $hasher, AppNotificator $appNotificator, Translator $translator, Formatter $formatter, \Twig_Environment $engine)
    {
        $this->hasher = $hasher;
        $this->appNotificator = $appNotificator;
        $this->translator = $translator;
        $this->formatter = $formatter;
        $this->twig = $engine;
    }

    public function onAppointmentCreated(AppointmentEvent $event)
    {

        $entity = $event->getAppointment();
        $patient = $entity->getPatient();

        $message = new Message();
        $message->setTag(Message::TAG_APPOINTMENT_CREATED)
            ->setRecipient($patient)
        ->setSubject($this->translator->trans('app.appointment.email.scheduled'))
        ->setRouteData(array(
            'route' => 'calendar_event_view',
            'parameters' => array(
                'event' => $this->hasher->encodeObject($entity),
            ),
        ))
        ->setBodyData(array(
            'template'=>'@App/Appointment/email.html.twig',
            'data'=>array(
                'appointment' => $entity,
            ),
        ));

        $message->compile();

        $this->appNotificator->sendMessage($message);

        /*
        if ($this->appNotificator->sendMessage($message)) {
            $event->getEntityManager()->persist($message);
            $this->computeEntityChangeSet($message, $event->getEntityManager());
        }
        */

        /*
        if ($email = $patient->getEmail()) {

            $body = $this->twig->render(
                '@App/Appointment/email.html.twig',
                array(
                    'appointment' => $entity,
                )
            );

            $message = $this->appMailer->createPracticionerMessage($entity->getOwner())
                ->setSubject(
                    $this->translator->trans('app.appointment.email.scheduled')
                )
                ->setTo($email, (string)$patient)
                ->setBody($body);

            $this->appMailer->send($message, true);

            $messageLog = new MessageLog();
            $messageLog->setType(Message::TYPE_EMAIL);
            $messageLog->setTag(MessageLog::TAG_APPOINTMENT_CREATED);
            $messageLog->setPatient($entity->getPatient());
            $messageLog->setRouteData(array(
                'route' => 'patient_view',
                'parameters' => array(
                    'id' => $this->hasher->encodeObject($entity->getPatient()),
                ),
            ));

            $event->getEntityManager()->persist($messageLog);
            $this->computeEntityChangeSet($messageLog, $event->getEntityManager());
        }
        */

    }

}
