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
use Faker\Factory;
use UserBundle\Entity\User;

class LoadUsersData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $user = new User();
        $user->setBusinessName('MyHS')
            ->setFirstName('System')
            ->setLastName('Admin')
            ->setTitle('Dr')
            ->setCountry($manager->getRepository('AppBundle:Country')->findAll()[0])
            ->setTimezone('+10:00')
            ->setEnabled(true)
            ->setEmail('admin@app.com')
            ->setUsername('admin')
            ->setRoles(array(User::ROLE_ADMIN))
            ->setPlainPassword('123123123123');
        $manager->persist($user);

        $user = new User();
        $user->setBusinessName($faker->company)
            ->setFirstName('Stepan')
            ->setLastName('Yudin')
            ->setTitle('Dr')
            ->setCountry($manager->getRepository('AppBundle:Country')->findAll()[0])
            ->setTimezone('+10:00')
            ->setEnabled(true)
            ->setEmail('stepan@yudin.com')
            ->setUsername('stepan')
            ->setRoles(array(User::ROLE_USER))
            ->setPlainPassword('123123123123');
        $manager->persist($user);

        $user = new User();
        $user->setBusinessName($faker->company)
            ->setFirstName('David')
            ->setLastName('Rooney')
            ->setTitle('Dr')
            ->setCountry($manager->getRepository('AppBundle:Country')->findAll()[0])
            ->setTimezone('+10:00')
            ->setEnabled(true)
            ->setEmail('david@rooney.com')
            ->setUsername('david')
            ->setRoles(array(User::ROLE_USER))
            ->setPlainPassword('123123123123');
        $manager->persist($user);

        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}
