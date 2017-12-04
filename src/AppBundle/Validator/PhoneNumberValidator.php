<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 14.09.2017
 * Time: 11:32
 */

namespace AppBundle\Validator;

use AppBundle\Entity\Phone;
use AppBundle\Utils\PhoneUtils;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PhoneNumberValidator extends ConstraintValidator
{

    /** @var PhoneUtils */
    protected $phoneUtils;

    public function __construct(PhoneUtils $phoneUtils)
    {
        $this->phoneUtils = $phoneUtils;
    }

    public static $path = 'phoneNumber';

    /**
     * @param Phone $phone
     * @return \AppBundle\Entity\Country|bool
     */
    protected function getCountry($phone)
    {
        if ($phone->getPatient()->getState()) {
            return $phone->getPatient()->getState()->getCountry();
        }
        return null;
    }

    /**
     * @param Phone $patient
     * @return string
     */
    protected function getPhoneNumber($patient)
    {
        return $patient->getPhoneNumber();
    }

    /**
     * @param Phone $object
     * @param Constraint $constraint
     */
    public function validate($object, Constraint $constraint)
    {
        $region = $this->phoneUtils->defaultRegion;

        if ($this->getCountry($object)) {
            $region = $this->getCountry($object)->getIsoCode();
        }

        if (!$this->phoneUtils->isValidPhone($this->getPhoneNumber($object), $region)) {
            $this->context->buildViolation($constraint->message)
                ->atPath($this::$path)
                ->addViolation();
        }
    }

}
