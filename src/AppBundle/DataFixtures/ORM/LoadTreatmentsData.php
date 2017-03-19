<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 13:05
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Country;
use AppBundle\Entity\Patient;
use AppBundle\Entity\PatientAlert;
use AppBundle\Entity\Phone;
use AppBundle\Entity\Product;
use AppBundle\Entity\State;
use AppBundle\Entity\Treatment;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LoadTreatmentsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($n = 1; $n <= 50; $n++) {
            $treatment = new Treatment();
            $treatment->setName('Treatment '.$n)
                ->setPrice(mt_rand(5000, 999999) / 100);

            $manager->persist($treatment);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}
