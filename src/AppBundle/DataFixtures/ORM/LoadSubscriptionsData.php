<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 10:39
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Subscription;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSubscriptionsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $plan = new Subscription();
        $plan->setName('Trial')
            ->setDuration('month')
            ->setPrice(0);
        $manager->persist($plan);

        $plan = new Subscription();
        $plan->setName('Month')
            ->setDuration('month')
            ->setPrice(10);
        $manager->persist($plan);

        $plan = new Subscription();
        $plan->setName('Year')
            ->setDuration('year')
            ->setPrice(100);
        $manager->persist($plan);

        $manager->flush();
    }

    public function getOrder()
    {
        return 0;
    }
}
