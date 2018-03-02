<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 18.12.17
 * Time: 16:16
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Event;
use AppBundle\Entity\EventResource;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\Patient;
use AppBundle\Entity\PatientAlert;
use AppBundle\Entity\UnavailableBlock;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Translation\Translator;
use UserBundle\Entity\User;

class RecallUtils
{
    /** @var  Hasher */
    protected $hasher;

    /** @var  Translator */
    protected $translator;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Session */
    protected $session;

    /** @var  RequestStack */
    protected $requestStack;

    /** @var  Formatter */
    protected $formatter;

    /** @var  TokenStorage */
    protected $tokenStorage;

    /** @var  User */
    protected $user;

    public function __construct(
        EntityManager $entityManager,
        Hasher $hasher,
        Translator $translator,
        RequestStack $requestStack,
        Formatter $formatter,
        TokenStorage $tokenStorage
    )
    {
        $this->hasher = $hasher;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
        $this->formatter = $formatter;
        $this->tokenStorage = $tokenStorage;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function getRecallsQb()
    {
        return $this->entityManager->getRepository('AppBundle:Recall')->createQueryBuilder('r');
    }

    public function getAllRecallsQb()
    {
        $qb = $this->getRecallsQb();

        $qb->orderBy('r.date', 'DESC');

        return $qb;
    }

    public function getNextRecallsQb()
    {
        $qb = $this->getRecallsQb();

        $qb->andWhere('r.date >= :date')
            ->orderBy('r.date', 'ASC')
            ->setParameters(array(
                'date' => new \DateTime(),
            ));

        $qb->andWhere($qb->expr()->isNull('r.completed'));

        return $qb;
    }

    public function getPrevRecallsQb()
    {
        $qb = $this->getRecallsQb();

        $qb->andWhere('r.date <= :date')
            ->orderBy('r.date', 'DESC')
            ->setParameters(array(
                'date' => new \DateTime(),
            ));

        $qb->andWhere($qb->expr()->isNull('r.completed'));

        return $qb;
    }

    public function getNextRecallsByPatientQb(Patient $patient)
    {
        $qb = $this->getNextRecallsQb();

        $qb->andWhere('r.patient = :patientId')
            ->setParameter('patientId', $patient->getId());

        return $qb;
    }

    public function getPrevRecallsByPatientQb(Patient $patient)
    {
        $qb = $this->getPrevRecallsQb();

        $qb->andWhere('r.patient = :patientId')
            ->setParameter('patientId', $patient->getId());

        return $qb;
    }

    public function getAvailableRecallsByPatient(Patient $patient)
    {
        $recallTypes = $this->entityManager->getRepository('AppBundle:RecallType')->findAll();
        $result = [];
        foreach ($recallTypes as $recallType) {
            if (($recallType->isBySms() || $recallType->isByCall()) && $patient->getMobilePhone()) {
                $result[] = $recallType;
            } elseif ($recallType->isByEmail() && $patient->getEmail()) {
                $result[] = $recallType;
            } else {
                $result[] = $recallType;
            }
        }
        return array_unique($result);
    }

}
