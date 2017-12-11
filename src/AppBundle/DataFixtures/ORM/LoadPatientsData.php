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
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPatientsData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        $users = $manager->getRepository('UserBundle:User')->findAll();
        $states = $manager->getRepository('AppBundle:State')->findAll();

        $file = $this->container->getParameter('kernel.root_dir') . '/../temp/fixtures/patients.xlsx';
        $inputFileType = \PHPExcel_IOFactory::identify($file);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($file);
        $worksheet = $objPHPExcel->setActiveSheetIndex(0);

        $getBDay = function($bday) {

            $separator = '/';
            if (mb_substr_count($bday,'-')) {
                $separator = '-';
            }

            $parts = explode($separator, $bday);

            if (mb_strlen($parts[2]) == 4) {
                $patern = 'd'.$separator.'m'.$separator.'Y';
            } else {
                $patern = 'd'.$separator.'m'.$separator.'y';
            }

            $bday = \DateTime::createFromFormat($patern, trim($bday));

            return $bday;

        };

        foreach ($users as $user) {
            foreach ($worksheet->getRowIterator() as $row) {
                if ($row->getRowIndex() > 1) {

                    $firstName = $worksheet->getCell('A' . $row->getRowIndex())->getValue();
                    $lastName = $worksheet->getCell('B' . $row->getRowIndex())->getValue();
                    $gender = $worksheet->getCell('C' . $row->getRowIndex())->getValue();
                    $bday = $worksheet->getCell('D' . $row->getRowIndex())->getFormattedValue();
                    $mobileNum = $worksheet->getCell('E' . $row->getRowIndex())->getValue();
                    $referrer = $worksheet->getCell('F' . $row->getRowIndex())->getValue();
                    $email = $worksheet->getCell('G' . $row->getRowIndex())->getValue();


                    $patient = new Patient();

                    $patient->setFirstName($firstName)
                        ->setLastName($lastName)
                        ->setDateOfBirth($getBDay($bday))
                        ->setGender($gender)
                        ->setMobilePhone($mobileNum)
                        ->setEmail($email)
                        ->setReferrer($referrer)
                        ->setState($states[mt_rand(0, count($states) - 1)])
                        ->setOwner($user);

                    $manager->persist($patient);

                }
            }

            /*
            $faker = Factory::create();
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

            for ($i = 0; $i < 80; $i++) {

                $gender = mt_rand(0, 1) == 1 ? 'Male' : 'Female';
                $firstName = $gender == 'Male' ? $faker->firstNameMale : $faker->firstNameFemale;
                $lastName = $faker->lastName;
                $birthDay = $faker->dateTimeBetween('-50 years', '-20 years');
                $state = $states[mt_rand(0, count($states) - 1)];
                $city = $faker->city;
                $smsNotification = (bool)mt_rand(0, 1);
                $emailNotification = (bool)mt_rand(0, 1);
                $bookingConfirmation = (bool)mt_rand(0, 1);
                $title = array_values($titles)[mt_rand(0, count(array_values($titles)) - 1)];
                $email = $faker->email;

                $patient = new Patient();
                $patient->setFirstName($firstName)
                    ->setLastName($lastName)
                    ->setDateOfBirth($birthDay)
                    ->setGender($gender)
                    //->setEmail($email)
                    ->setState($state)
                    ->setCity($city)
                    ->setAutoRemindSMS($smsNotification)
                    ->setAutoRemindEmail($emailNotification)
                    ->setBookingConfirmationEmail($bookingConfirmation)
                    ->setReferrer('Google')
                    ->setMobilePhone('1300 551 119')
                    ->setTitle($title)
                    ->setOwner($user);

                //for ($p = 0; $p < mt_rand(0, 4); $p++) {
                //    $phone = new Phone();
                //    $phone->setPhoneNumber($faker->phoneNumber);
                //    $phone->setPhoneType('Mobile');
                //    $patient->addPhone($phone);
                //}

                for ($a = 0; $a < mt_rand(0, 3); $a++) {
                    $alert = new PatientAlert();
                    $alert->setText('Important patient alert')
                        ->setOwner($user);
                    $patient->addAlert($alert);
                }

                $manager->persist($patient);
            }*/
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}
