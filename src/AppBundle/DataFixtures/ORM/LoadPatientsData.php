<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 14:53
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Country;
use AppBundle\Entity\Patient;
use AppBundle\Entity\PatientAlert;
use AppBundle\Entity\Phone;
use AppBundle\Entity\State;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LoadPatientsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $states = $manager->getRepository('AppBundle:State')->findAll();
        $titles = array(
            'Dr' => 'Dr',
            'Master' => 'Master',
            'Professor' => 'Professor',
            'Mr' => 'Mr',
            'Sir' => 'Sir',
            'Ms' => 'Ms',
            'Mrs' => 'Mrs',
            'Miss' => 'Miss',
            'Madam' => 'Madam',
        );

        $users = $manager->getRepository('UserBundle:User')->findAll();

        foreach ($users as $user) {
            for ($i = 0; $i < 80; $i++) {

                $gender = mt_rand(0, 1) == 1 ? 'Male' : 'Female';
                $firstName = $gender == 'Male' ? $faker->firstNameMale : $faker->firstNameFemale;
                $lastName = $faker->lastName;
                $birthDay = $faker->dateTimeBetween('-50 years', '-20 years');
                $email = $faker->email;
                $state = $states[mt_rand(0, count($states) - 1)];
                $city = $faker->city;
                $smsNotification = (bool)mt_rand(0, 1);
                $emailNotification = (bool)mt_rand(0, 1);
                $bookingConfirmation = (bool)mt_rand(0, 1);
                $title = array_values($titles)[mt_rand(0, count(array_values($titles)) - 1)];

                $patient = new Patient();
                $patient->setFirstName($firstName)
                    ->setLastName($lastName)
                    ->setDateOfBirth($birthDay)
                    ->setGender($gender)
                    ->setEmail($email)
                    ->setState($state)
                    ->setCity($city)
                    ->setAutoRemindSMS($smsNotification)
                    ->setAutoRemindEmail($emailNotification)
                    ->setBookingConfirmationEmail($bookingConfirmation)
                    ->setTitle($title)
                    ->setOwner($user);

                for ($p = 0; $p < mt_rand(0, 4); $p++) {
                    $phone = new Phone();
                    $phone->setPhoneNumber($faker->phoneNumber);
                    $phone->setPhoneType('Mobile');
                    $patient->addPhone($phone);
                }

                for ($a = 0; $a < mt_rand(0, 3); $a++) {
                    $alert = new PatientAlert();
                    $alert->setText('Important patient alert');
                    $patient->addAlert($alert);
                }

                $manager->persist($patient);
            }
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}
