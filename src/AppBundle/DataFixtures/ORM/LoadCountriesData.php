<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 03.03.2017
 * Time: 20:07
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Country;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadCountriesData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $countries = array(
            'Australia'
        );

        foreach($countries as $countryName) {
            $country = new Country();
            $country->setName($countryName);
            $manager->persist($country);
        }

        $manager->flush();
    }
}
