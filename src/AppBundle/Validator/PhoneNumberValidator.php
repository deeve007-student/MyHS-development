<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 14.09.2017
 * Time: 11:32
 */

namespace AppBundle\Validator;

use AppBundle\Entity\Phone;
use Symfony\Component\Validator\Constraint;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumberValidator as MisdPhoneNumberValidator;
use Symfony\Component\Validator\ConstraintViolationInterface;

class PhoneNumberValidator extends MisdPhoneNumberValidator
{

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
        return false;
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
        $constraint->defaultRegion = "AU"; //Todo: remove this hack in future

        if($this->getCountry($object)) {
            $constraint->defaultRegion = $this->getCountry($object)->getIsoCode();
        }

        parent::validate($this->getPhoneNumber($object), $constraint);

        /** @var ConstraintViolationInterface $violation */
        foreach ($this->context->getViolations() as $n => $violation) {

            $countryMessageAppendix = '';
            if($this->getCountry($object)) {
                $countryMessageAppendix = ' (' . $this->getCountry($object) . ')';
            }

            $this->context->buildViolation(
                $constraint->getMessage() . $countryMessageAppendix,
                array('{{ type }}' => $constraint->getType(), '{{ value }}' => $this->getPhoneNumber($object))
            )
                ->atPath($this::$path)
                ->addViolation();

            $this->context->getViolations()->remove($n);

        }
    }

}
