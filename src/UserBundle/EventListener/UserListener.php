<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.11.2016
 * Time: 19:39
 */

namespace UserBundle\EventListener;

use AppBundle\Entity\CalendarSettings;
use AppBundle\Entity\CommunicationsSettings;
use AppBundle\Entity\EventResource;
use AppBundle\Entity\InvoiceSettings;
use AppBundle\Entity\TreatmentNoteField;
use AppBundle\Entity\TreatmentNoteTemplate;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Translation\Translator;
use UserBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UserListener
{

    use RecomputeChangesTrait;

    /** @var  Translator */
    protected $translator;

    /** @var  User[] */
    protected $newUsers;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $user = $args->getEntity();
        $em = $args->getEntityManager();

        if ($user instanceof User) {

            $user->setPatientNumber(0);
            $user->addRole(User::ROLE_DEFAULT)
                ->setApiKey(md5(microtime() . rand()))
                ->setSubscription($em->getRepository('AppBundle:Subscription')->findOneBy(array('name' => 'Trial')))
                ->setFirstLogin(true);

            $this->newUsers[] = $user;

            $this->setUsername($user);
            $this->setCountry($user, $em);

        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if (count($this->newUsers) > 0) {
            foreach ($this->newUsers as $n => $newUser) {
                $this->createDefaultTreatmentNoteTemplate($newUser, $args->getEntityManager());
                $this->createCalendarSettings($newUser, $args->getEntityManager());
                $this->createInvoiceSettings($newUser, $args->getEntityManager());
                $this->createCommunicationsSettings($newUser, $args->getEntityManager());
            }
            $this->newUsers = array();
            $args->getEntityManager()->flush();
        }
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $user = $args->getEntity();

        if ($user instanceof User) {
            $this->setUsername($user);
        }
    }

    protected function setUsername(User $user)
    {
        $user->setUsername($user->getEmail())
            ->setUsernameCanonical($user->getEmail());
    }

    protected function setCountry(User $user, EntityManager $entityManager)
    {
        $user->setCountry(
            $entityManager->getRepository('AppBundle:Country')->findOneBy(
                array('name' => 'Australia')
            )
        );
    }

    protected function createDefaultTreatmentNoteTemplate(User $user, EntityManager $entityManager)
    {
        $tnTemplate = new TreatmentNoteTemplate();
        $tnTemplate->setName('Default')
            ->setOwner($user)
            ->setDefault(true);

        // Todo: move these default values to translation
        $tnTemplateFields = array();
        $tnTemplateFields[] = array('Note summary', true);
        $tnTemplateFields[] = array('Presenting complaint', false);
        $tnTemplateFields[] = array('Complaint history', false);
        $tnTemplateFields[] = array('Assessment', false);
        $tnTemplateFields[] = array('Treatment', false);
        $tnTemplateFields[] = array('Exercise', false);
        $tnTemplateFields[] = array('Supplements & home advice', false);

        $position = 1;
        foreach ($tnTemplateFields as $tnTemplateField) {
            $field = new TreatmentNoteField();
            $field->setName($tnTemplateField[0])
                ->setMandatory($tnTemplateField[1])
                ->setOwner($user)
                ->setPosition($position);

            $position++;
            $tnTemplate->addTreatmentNoteField($field);
        }

        $entityManager->persist($tnTemplate);
    }

    protected function createDefaultResources(User $user, CalendarSettings $calendarSettings, EntityManager $entityManager)
    {
        $resources = array(
            1 => $this->translator->trans('app.event_resource.defaults.resource_name', ['%n%' => 1]),
            2 => $this->translator->trans('app.event_resource.defaults.resource_name', ['%n%' => 2]),
        );

        $n = 0;
        foreach ($resources as $resourcePosition => $resourceName) {
            $resource = new EventResource();
            $resource->setName($resourceName)
                ->setPosition($resourcePosition)
                ->setCalendarSettings($calendarSettings)
                ->setOwner($user);

            $resource->setDefault(false);
            if ($n == 0) {
                $resource->setDefault(true);
            }

            $entityManager->persist($resource);
            $n++;
        }
    }

    protected function createCalendarSettings(User $user, EntityManager $entityManager)
    {
        $data = new CalendarSettings();
        $data->setWorkDayStart('09:00 AM');
        $data->setWorkDayEnd('05:00 PM');
        $data->setTimeInterval(15);
        $data->setOwner($user);

        $this->createDefaultResources($user, $data, $entityManager);

        $entityManager->persist($data);
    }

    protected function createInvoiceSettings(User $user, EntityManager $entityManager)
    {
        $data = new InvoiceSettings();
        $data->setInvoiceNumber(1);
        $data->setDueWithin(0);
        $data->setInvoiceTitle('Invoice');
        $data->setOwner($user);
        $data->setInvoiceEmail(<<<EOT
Dear {{ patientName }},

Please find attached invoice #{{ invoiceNumber }} for the amount of {{ invoiceTotal }}.

If you have questions about this invoice, please contact us immediately.

Regards,
{{ businessName }}
EOT
        );

        $entityManager->persist($data);
    }


    protected function createCommunicationsSettings(User $user, EntityManager $entityManager)
    {
        $data = new CommunicationsSettings();

        $data->setOwner($user);

        $data->setFromEmailAddress($user->getEmail());

        $data->setAppointmentCreationEmail(<<<EOT
Dear {{ patientName }},

This email is to confirm your appointment with {{ practitionerName }} on {{ appointmentDate }} at {{ appointmentTime }}.

Regards, 
{{ businessName }}
EOT
        );

        $data->setAppointmentCreationSms(<<<EOT
This message is to confirm your appointment with {{ practitionerName }} on {{ appointmentDate }} at {{ appointmentTime }}. Thanks, {{ businessName }}
EOT
        );

        $data->setAppointmentReminderEmail(<<<EOT
Dear {{ patientName }},

This email is to remind you of your appointment with {{ practitionerName }} on {{ appointmentDate }} at {{ appointmentTime }}.

Please let us know if you will have any problems with attending this scheduled appointment.

Regards, 
{{ businessName }}
EOT
        );

        $data->setAppointmentReminderSms(<<<EOT
This message is to remind you of your appointment with {{ practitionerName }} on {{ appointmentDate }} at {{ appointmentTime }}. Thanks, {{ businessName }}
EOT
        );

        $data->setRecallEmailSubject(<<<EOT
Do you wanted to schedule an appointment with us soon?
EOT
        );

        $data->setRecallEmail(<<<EOT
Dear {{ patientName }},

Just a quick message to see if you wanted to schedule an appointment with us soon? If so please let us know and we will book you in as soon as we can.

Regards, 
{{ businessName }}
EOT
        );

        $data->setRecallSms(<<<EOT
Hi {{ patientName }}, just a quick message to see if you wanted to schedule an appointment with us soon? If so please let us know and we will book you in as soon as we can. Thanks, {{ businessName }}
EOT
        );

        $data->setWhenRemainderEmailSentDay(1);
        $data->setWhenRemainderEmailSentTime(new \DateTime('8 AM'));

        $data->setWhenRemainderSmsSentDay(1);
        $data->setWhenRemainderSmsSentTime(new \DateTime('8 AM'));

        $entityManager->persist($data);
    }

    protected function setTimezone(User $user)
    {
        $user->setTimezone('+10:00');
    }

}
