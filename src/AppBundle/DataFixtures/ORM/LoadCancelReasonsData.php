<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 16.06.2017
 * Time: 17:05
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\CancelReason;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCancelReasonsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $reasons = array(
            'Feeling better',
            'Condition worse',
            'Sick',
            'Away',
            'Work',
            'Other',
        );

        foreach ($reasons as $reasonPos => $reasonName) {
            $reason = new CancelReason();
            $reason->setName($reasonName);
            $reason->setPosition($reasonPos);
            $manager->persist($reason);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 0;
    }
}
