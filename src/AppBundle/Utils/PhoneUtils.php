<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 14.09.2017
 * Time: 13:25
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Patient;
use Doctrine\ORM\EntityManager;
use UserBundle\Entity\User;

class PhoneUtils
{

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Formatter */
    protected $formatter;

    public function __construct(EntityManager $entityManager, Formatter $formatter)
    {
        $this->entityManager = $entityManager;
        $this->formatter = $formatter;
    }

    /**
     * @param $internationalPhoneNumber
     * @return User|bool
     */
    public function getUserByPhoneNumber($internationalPhoneNumber)
    {
        // Todo: implement later
    }

    /**
     * @param $internationalPhoneNumber
     * @param User|null $user
     * @return Patient|bool
     */
    public function getPatientByPhoneNumber($internationalPhoneNumber, User $user = null)
    {

        $patientsQb = $this->entityManager->getRepository('AppBundle:Patient')->createQueryBuilder('p');

        if ($user) {
            $patientsQb->where('p.owner = :user')
                ->setParameter('user', $user);
        }

        /** @var Patient[] $patients */
        $patients = $patientsQb->getQuery()->getResult();

        foreach ($patients as $patient) {

            if ($this->formatter->formatPhone($patient) === $internationalPhoneNumber) {
                return $patient;
            }

            foreach ($patient->getPhones() as $phone) {
                if ($this->formatter->formatPhone($phone) === $internationalPhoneNumber) {
                    return $patient;
                }
            }

        }

        return false;

    }

}
