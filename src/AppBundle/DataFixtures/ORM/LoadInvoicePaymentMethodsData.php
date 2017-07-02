<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 13:05
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\InvoicePaymentMethod;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadInvoicePaymentMethodsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $paymentMethods = array(
            'Credit card',
            'Cash',
            'Cheque',
            'Bank transfer',
            'Hicaps',
        );

        foreach ($paymentMethods as $paymentMethodName) {
            $method = new InvoicePaymentMethod();
            $method->setName($paymentMethodName);
            $manager->persist($method);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}
