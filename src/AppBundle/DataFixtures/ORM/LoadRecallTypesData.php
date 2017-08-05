<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 05.08.2017
 * Time: 21:34
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Concession;
use AppBundle\Entity\RecallFor;
use AppBundle\Entity\RecallType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRecallTypesData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $types = array(
            'Email',
            'SMS',
            'Email & SMS',
            'Manual',
        );

            foreach ($types as $recallTypeName) {
                $recallType = new RecallType();
                $recallType->setName($recallTypeName);

                $manager->persist($recallType);
            }

        $fors = array(
            'FTKA',
            'Did not reschedule',
            'Care call',
            'Cancelled',
            'Check notes',
            'Asked us to call to reschedule',
            'Due for next appointment',
        );

            foreach ($fors as $forName) {
                $recallFor = new RecallFor();
                $recallFor->setName($forName);

                $manager->persist($recallFor);
            }

        $manager->flush();
    }

    public function getOrder()
    {
        return 25;
    }
}
