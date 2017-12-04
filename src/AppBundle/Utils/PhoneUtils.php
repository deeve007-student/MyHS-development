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
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\VarDumper\VarDumper;
use UserBundle\Entity\User;

class PhoneUtils
{
    public $defaultRegion = "US";

    /** @var  EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager, TokenStorage $tokenStorage)
    {
        $this->entityManager = $entityManager;

        if ($tokenStorage->getToken() &&
            $tokenStorage->getToken()->getUser() &&
            $tokenStorage->getToken()->getUser()->getCountry()
        ) {
            $this->defaultRegion = $tokenStorage->getToken()->getUser()->getCountry()->getIsoCode();
        }
    }

    /**
     * @param $internationalPhoneNumber
     * @return User|bool
     */
    public function getUserByPhoneNumber($internationalPhoneNumber)
    {
        // Todo: implement later
        return false;
    }

    public function getPhoneNumberFromString($phoneStr, $region = null)
    {
        $phoneUtils = PhoneNumberUtil::getInstance();

        if (!$region) {
            $region = $this->defaultRegion;
        }

        try {
            $phoneNumber = $phoneUtils->parse($phoneStr, $region);
            if (!$phoneUtils->isValidNumber($phoneNumber)) {
                return false;
            }
        } catch (\Exception $exception) {
            return false;
        }

        return $phoneNumber;
    }

    /**
     * Validate whether phone is ok
     *
     * @param $phoneStr
     * @param string $region
     * @return bool
     */
    public function isValidPhone($phoneStr, $region = null)
    {
        if ($phoneNumber = $this->getPhoneNumberFromString($phoneStr, $region)) {
            return true;
        }
        return false;
    }

    /**
     * Validate whether phone is ok and is mobile
     *
     * @param $phoneStr
     * @param string $region
     * @return bool
     */
    public function isValidMobilePhone($phoneStr, $region = null)
    {
        $phoneUtils = PhoneNumberUtil::getInstance();

        if ($phoneNumber = $this->getPhoneNumberFromString($phoneStr, $region)) {
            if ($phoneUtils->getNumberType($phoneNumber) == 1) {
                return true;
            }
        }
        return false;
    }

    public function formatPhoneCallable($phone, $country = null)
    {
        $phoneUtils = PhoneNumberUtil::getInstance();

        if ($number = $this->getPhoneNumberFromString($phone, $country)) {
            $phone = $phoneUtils->format($number, PhoneNumberFormat::INTERNATIONAL);
        }

        return preg_replace('/[^\d\+]+/', '', $phone);
    }

    public function formatPhonePretty($phone, $region)
    {
        $phoneUtils = PhoneNumberUtil::getInstance();
        if ($number = $this->getPhoneNumberFromString($phone, $region)) {
            return $phoneUtils->format($number, PhoneNumberFormat::INTERNATIONAL);
        }

        return $phone;
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

            if ($this->formatPhoneCallable($patient) === $internationalPhoneNumber) {
                return $patient;
            }

            foreach ($patient->getPhones() as $phone) {
                if ($this->formatPhoneCallable($phone) === $internationalPhoneNumber) {
                    return $patient;
                }
            }

        }

        return false;

    }

}
