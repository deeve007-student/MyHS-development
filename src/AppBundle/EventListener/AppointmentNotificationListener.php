<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.06.2017
 * Time: 18:23
 */

namespace AppBundle\EventListener;

use AppBundle\Event\AppointmentEvent;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use AppBundle\Utils\AppMailer;
use AppBundle\Utils\Formatter;
use Symfony\Component\Translation\Translator;

class AppointmentNotificationListener
{

    use RecomputeChangesTrait;

    /** @var AppMailer */
    protected $appMailer;

    /** @var Translator */
    protected $translator;

    /** @var Formatter */
    protected $formatter;

    /** @var \Twig_Environment */
    protected $twig;

    public function __construct(AppMailer $appMailer, Translator $translator, Formatter $formatter, \Twig_Environment $engine)
    {
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
        }

    }

}
