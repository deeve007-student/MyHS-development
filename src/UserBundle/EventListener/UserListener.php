<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.11.2016
 * Time: 19:39
 */

namespace UserBundle\EventListener;

use AppBundle\Entity\TreatmentNoteField;
use AppBundle\Entity\TreatmentNoteTemplate;
use AppBundle\EventListener\Traits\RecomputeChangesTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use UserBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UserListener
{

    use RecomputeChangesTrait;

    /** @var  User[] */
    protected $newUsers;

    public function prePersist(LifecycleEventArgs $args)
    {
        $user = $args->getEntity();
        $em = $args->getEntityManager();

        if ($user instanceof User) {

            $user->addRole(User::ROLE_DEFAULT)
                ->setApiKey(md5(microtime().rand()))
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
            foreach($this->newUsers as $n => $newUser) {
                $this->createDefaultTreatmentNoteTemplate($newUser, $args->getEntityManager());
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
        $tnt = new TreatmentNoteTemplate();
        $tnt->setName('Default')
            ->setOwner($user);

        $tntFields = array();
        $tntFields[] = array('Note summary', true);
        $tntFields[] = array('Presenting complaint', false);
        $tntFields[] = array('Complaint history', false);
        $tntFields[] = array('Assessment', false);
        $tntFields[] = array('Treatment', false);
        $tntFields[] = array('Exercise', false);
        $tntFields[] = array('Supplements & home advice', false);

        $position = 1;
        foreach ($tntFields as $tntField) {
            $field = new TreatmentNoteField();
            $field->setName($tntField[0])
                ->setMandatory($tntField[1])
                ->setOwner($user)
                ->setPosition($position);

            $position++;
            $tnt->addTreatmentNoteField($field);
        }

        $entityManager->persist($tnt);
        //$this->computeEntityChangeSet($tnt, $entityManager);
        //$entityManager->flush();

    }

    protected function setTimezone(User $user)
    {
        $user->setTimezone('+10:00');
    }

}
