<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 07.03.2017
 * Time: 10:47
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Country;
use AppBundle\Entity\PatientRelationship;
use AppBundle\Entity\State;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPatientRelationshipData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $relationships = array(
            'Parent' => 'Child',
            'Child' => 'Parent',
            'Sibling' => 'Sibling',
            'Partner' => 'Partner',
            'Spouse' => 'Spouse',
            'Relative' => 'Relative',
            'Other' => 'Other',
        );

        foreach ($relationships as $relationshipName => $reverseRelationshipName) {
            $relationship = new PatientRelationship();
            $relationship->setName($relationshipName);
            $relationship->setReverseName($reverseRelationshipName);
            $manager->persist($relationship);
        }

        $manager->flush();

    }

    public function getOrder()
    {
        return 0;
    }
}
