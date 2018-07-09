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
use AppBundle\Utils\Templater;
use Symfony\Component\Translation\Translator;
use Symfony\Component\VarDumper\VarDumper;

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

    /** @var Templater */
    protected $templater;

    /**
     * AppointmentNotificationListener constructor.
     * @param Hasher $hasher
     * @param AppNotificator $appNotificator
     * @param Translator $translator
     * @param Formatter $formatter
     * @param \Twig_Environment $engine
     */
    public function __construct(Hasher $hasher, AppNotificator $appNotificator, Translator $translator, Formatter $formatter, \Twig_Environment $engine, Templater $templater)
    {
        $this->hasher = $hasher;
        $this->appNotificator = $appNotificator;
        $this->translator = $translator;
        $this->formatter = $formatter;
        $this->twig = $engine;
        $this->templater = $templater;
    }

    /**
     * @param AppointmentEvent $event
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onAppointmentCreated(AppointmentEvent $event)
    {
        $entity = $event->getAppointment();

        if ($entity->getRecurrency()->getFirstEvent() === $entity) {

            /** @var AppointmentPatient[] $patients */
            $patients = array_map(function (AppointmentPatient $appointmentPatient) {
                return $appointmentPatient->getPatient();
            }, $entity->getAppointmentPatients()->toArray());

            foreach ($patients as $patient) {

                $qb = $event->getEntityManager()->getRepository('AppBundle:AppointmentPatient')->createQueryBuilder('ap');

                $firstAppointmentForPatient = $qb->andWhere('ap.patient = :patient')
                    ->setParameter('patient', $patient)
                    ->orderBy('ap.createdAt', 'ASC')
                    ->setMaxResults(1)
                    ->getQuery()->getOneOrNullResult();

                $isFirstAppointmentForPatient = $firstAppointmentForPatient->getAppointment() == $entity ? true : false;
                $messageTemplate = $isFirstAppointmentForPatient ? $entity->getOwner()->getCommunicationsSettings()->getNewPatientFirstAppointmentEmail() : $entity->getOwner()->getCommunicationsSettings()->getAppointmentCreationEmail();

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
                    ->setBodyData(
                        $this->templater->compile($messageTemplate, [
                            'patientName' => [$patient],
                            'businessName' => [$entity->getOwner()->getBusinessName()],
                            'appointmentDate' => [$entity->getStart(),'app_date_and_week_day_full'],
                            'appointmentTime' => [$entity->getStart(),'app_time'],
                        ])
                    );

                if ($entity->getTreatment()->getAttachment()) {
                    $message->addAttachment($entity->getTreatment()->getAttachment()->getRealPath());
                }
                if ($isFirstAppointmentForPatient && !is_null($entity->getOwner()->getCommunicationsSettings()->getFileName())) {
                    $message->addAttachment($entity->getOwner()->getCommunicationsSettings()->getFile()->getRealPath());
                }

                $message->compile($this->twig, $this->formatter);

                $this->appNotificator->sendMessage($message);

            }

        }

    }

}
