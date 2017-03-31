<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 31.03.2017
 * Time: 19:46
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Concession;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadConcessionsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $concessions = array('Student', 'Pensioner');
        $users = $manager->getRepository('UserBundle:User')->findAll();

        foreach ($users as $user) {
            foreach ($concessions as $concessionName) {
                $concession = new Concession();
                $concession->setName($concessionName)
                    ->setOwner($user);

                $manager->persist($concession);
            }
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 25;
    }
}
