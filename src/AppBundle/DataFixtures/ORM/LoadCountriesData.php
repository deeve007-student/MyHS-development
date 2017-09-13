<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 03.03.2017
 * Time: 20:07
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Country;
use AppBundle\Entity\State;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCountriesData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $countriesAndStates = array(
            'Australia' => array(
                'iso' => 'AU',
                'states' => array(
                    'NSW',
                    'VIC',
                    'QLD',
                    'TAS',
                    'SA',
                    'WA',
                    'NT',
                    'ACT',
                ),
            ),
        );

        foreach ($countriesAndStates as $countryName => $data) {
            $country = new Country();
            $country->setName($countryName)
                ->setIsoCode($data['iso']);

            foreach ($data['states'] as $stateName) {
                $state = new State();
                $state->setName($stateName);
                $country->addState($state);
            }

            $manager->persist($country);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 0;
    }
}
