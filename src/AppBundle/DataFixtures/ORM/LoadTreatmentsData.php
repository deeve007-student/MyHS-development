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
            for ($n = 1; $n <= 6; $n++) {
                $treatment = new Treatment();
                $treatment->setName($user->getFirstName() . '\'s treatment ' . $n)
                    ->setPrice(mt_rand(5000, 999999) / 100)
                    ->setOwner($user);

                if ($n == 2) {
                    $treatment->setCalendarColour('#cc0000');
                    $treatment->setName($treatment->getName() . ' (red)');
                }

                if ($n == 3) {
                    $treatment->setCalendarColour('#FFC300');
                    $treatment->setName($treatment->getName() . ' (orange)');
                }
                if ($n == 4) {
                    $treatment->setCalendarColour('#3E8FC1');
                    $treatment->setName($treatment->getName() . ' (blue)');
                }
                if ($n == 5) {
                    $treatment->setCalendarColour('#C13E9F');
                    $treatment->setName($treatment->getName() . ' (violet)');
                }
                if ($n == 6) {
                    $treatment->setCalendarColour('#900C3F');
                    $treatment->setName($treatment->getName() . ' (purple)');
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
