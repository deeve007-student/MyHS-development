<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 13:05
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProductsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository('UserBundle:User')->findAll();

        foreach ($users as $user) {
            for ($n = 1; $n <= 200; $n++) {
                $product = new Product();
                $product->setName($user->getFirstName().'\'s product '.$n)
                    ->setPrice(mt_rand(5000, 999999) / 100)
                    ->setOwner($user);

                $manager->persist($product);
            }
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}