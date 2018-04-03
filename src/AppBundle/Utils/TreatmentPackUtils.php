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
use AppBundle\Entity\Treatment;
use AppBundle\Entity\TreatmentPackCredit;
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

class TreatmentPackUtils
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

    public function getAvailableTreatmentPack(Patient $patient, Treatment $treatment)
    {
        $qb = $this->entityManager->getRepository('AppBundle:TreatmentPackCredit')->createQueryBuilder('tp');
        $qb->andWhere('tp.patient = :patient')
            ->setParameter('patient', $patient);

        /** @var TreatmentPackCredit[] $patientPacks */
        if ($patientPacks = $qb->getQuery()->getResult()) {
            foreach ($patientPacks as $pack) {
                if ($pack->getTreatment()->getId() === $treatment->getId()) {
                    return $pack;
                }
            }
        }

        return null;
    }

}
