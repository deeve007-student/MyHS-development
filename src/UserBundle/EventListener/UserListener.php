<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.11.2016
 * Time: 19:39
 */

namespace UserBundle\EventListener;

use AppBundle\Entity\CalendarData;
use AppBundle\Entity\EventResource;
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

            $user->addRole(User::ROLE_DEFAULT)
                ->setApiKey(md5(microtime() . rand()))
                ->setSubscription($em->getRepository('AppBundle:Subscription')->findOneBy(array('name' => 'Trial')))
                ->setInvoiceCounter(0)
                ->setFirstLogin(true);

            $this->newUsers[] = $user;

            $this->setUsername($user);
            $this->setTimezone($user);
            $this->setCountry($user, $em);

        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if (count($this->newUsers) > 0) {
            foreach ($this->newUsers as $n => $newUser) {
                $this->createDefaultTreatmentNoteTemplate($newUser, $args->getEntityManager());
                $this->createCalendarData($newUser, $args->getEntityManager());
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

    protected function createDefaultResources(User $user, CalendarData $calendarData, EntityManager $entityManager)
    {
        $resources = array(
            1 => $this->translator->trans('app.event_resource.defaults.resource_1'),
            2 => $this->translator->trans('app.event_resource.defaults.resource_2'),
        );

        $n = 0;
        foreach ($resources as $resourcePosition => $resourceName) {
            $resource = new EventResource();
            $resource->setName($resourceName)
                ->setPosition($resourcePosition)
                ->setCalendarData($calendarData)
                ->setOwner($user);

            $resource->setDefault(false);
            if ($n == 0) {
                $resource->setDefault(true);
            }

            $entityManager->persist($resource);
            $n++;
        }
    }

    protected function createCalendarData(User $user, EntityManager $entityManager)
    {
        $data = new CalendarData();
        $data->setWorkDayStart('09:00 AM');
        $data->setWorkDayEnd('05:00 PM');
        $data->setTimeInterval(15);
        $data->setOwner($user);

        $this->createDefaultResources($user, $data, $entityManager);

        $entityManager->persist($data);
    }

    protected function setTimezone(User $user)
    {
        $user->setTimezone('+10:00');
    }

}
