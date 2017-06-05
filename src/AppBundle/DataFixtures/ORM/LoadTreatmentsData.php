<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 13:05
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Treatment;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTreatmentsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository('UserBundle:User')->findAll();

        foreach ($users as $user) {
            for ($n = 1; $n <= 100; $n++) {
                $treatment = new Treatment();
                $treatment->setName($user->getFirstName().'\'s treatment '.$n)
                    ->setPrice(mt_rand(5000, 999999) / 100)
                    ->setOwner($user);

                if (round($n / 2) == $n / 2) {
                    $treatment->setCalendarColour('#cc0000');
                    $treatment->setName($treatment->getName().' (red)');
                }

                $manager->persist($treatment);
            }
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}
