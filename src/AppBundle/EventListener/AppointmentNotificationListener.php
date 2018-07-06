<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 18:23
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\AppointmentPatient;
use AppBundle\Entity\Message;
use AppBundle\Event\AppointmentEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use AppBundle\Utils\AppNotificator;
use AppBundle\Utils\Formatter;
use AppBundle\Utils\Hasher;
use Symfony\Component\Translation\Translator;

/**
 * Class AppointmentNotificationListener
 */
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

    /**
     * AppointmentNotificationListener constructor.
     * @param Hasher $hasher
     * @param AppNotificator $appNotificator
     * @param Translator $translator
     * @param Formatter $formatter
     * @param \Twig_Environment $engine
     */
    public function __construct(Hasher $hasher, AppNotificator $appNotificator, Translator $translator, Formatter $formatter, \Twig_Environment $engine)
    {
        $this->hasher = $hasher;
        $this->appNotificator = $appNotificator;
        $this->translator = $translator;
        $this->formatter = $formatter;
        $this->twig = $engine;
    }

    /**
     * @param AppointmentEvent $event
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onAppointmentCreated(AppointmentEvent $event)
    {
        $entity = $event->getAppointment();

        if ($entity->getRecurrency()->getFirstEvent() === $entity) {
            
            $patients = array_map(function (AppointmentPatient $appointmentPatient) {
                return $appointmentPatient->getPatient();
            }, $entity->getAppointmentPatients()->toArray());

            foreach ($patients as $patient) {

                $message = new Message();
                $message->setTag(Message::TAG_APPOINTMENT_CREATED)
                    ->setRecipient($patient)
                    ->setSubject($this->translator->trans('app.appointment.email.scheduled'))
                    ->setRouteData(array(
                        'route' => 'calendar_appointment_view',
                        'parameters' => array(
                            'event' => $this->hasher->encodeObject($entity),
                        ),
                    ))
                    ->setBodyData(array(
                        'template' => '@App/Appointment/email.html.twig',
                        'data' => array(
                            'appointment' => $entity,
                            'patient' => $patient,
                        ),
                    ));

                if ($entity->getTreatment()->getAttachment()) {
                    $message->addAttachment($entity->getTreatment()->getAttachment()->getRealPath());
                    // Todo: add sent attachment to patient's attachments
                }

                $message->compile($this->twig, $this->formatter);

                $this->appNotificator->sendMessage($message);

            }

        }

    }

}
