<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 03.03.2017
 * Time: 20:11
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUsersData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstName('Stepan')
            ->setLastName('Yudin')
            ->setTitle('Dr')
            ->setCountry($manager->getRepository('AppBundle:Country')->findAll()[0])
            ->setTimezone('+8:00')
            ->setEnabled(true)
            ->setEmail('stepan.sib@gmail.com')
            ->setUsername('stepan.sib')
            ->setRoles(array(User::ROLE_USER))
            ->setPlainPassword('123123');

        $manager->persist($user);
        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}
