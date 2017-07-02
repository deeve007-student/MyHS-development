<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 18:23
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\MessageLog;
use AppBundle\Event\AppointmentEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use AppBundle\Utils\AppMailer;
use AppBundle\Utils\Formatter;
use AppBundle\Utils\Hasher;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\Translator;

class AppointmentNotificationListener
{

    use RecomputeChangesTrait;

    /** @var Hasher */
    protected $hasher;

    /** @var AppMailer */
    protected $appMailer;

    /** @var Translator */
    protected $translator;

    /** @var Formatter */
    protected $formatter;

    /** @var \Twig_Environment */
    protected $twig;

    public function __construct(Hasher $hasher, AppMailer $appMailer, Translator $translator, Formatter $formatter, \Twig_Environment $engine)
    {
        $this->hasher = $hasher;
        $this->appMailer = $appMailer;
        $this->translator = $translator;
        $this->formatter = $formatter;
        $this->twig = $engine;
    }

    public function onAppointmentCreated(AppointmentEvent $event)
    {

        $entity = $event->getAppointment();
        $patient = $entity->getPatient();

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
            $messageLog->setType(MessageLog::TYPE_EMAIL);
            $messageLog->setCategory(MessageLog::CATEGORY_APPOINTMENT_CREATED);
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

    }

}
