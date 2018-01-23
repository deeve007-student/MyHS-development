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
            'Email' => array(
                'by_email' => true,
                'by_sms' => false,
                'by_call' => false,
            ),
            'Phone' => array(
                'by_email' => false,
                'by_sms' => false,
                'by_call' => true,
            ),
            'SMS' => array(
                'by_email' => false,
                'by_sms' => true,
                'by_call' => false,
            ),
            'Email & SMS' => array(
                'by_email' => true,
                'by_sms' => true,
                'by_call' => false,
            ),
            'Manual' => array(
                'by_email' => false,
                'by_sms' => false,
                'by_call' => false,
            ),
        );

        foreach ($types as $recallTypeName => $options) {
            $recallType = new RecallType();
            $recallType->setName($recallTypeName);

            if ($options['by_email']) {
                $recallType->setByEmail(true);
            }

            if ($options['by_sms']) {
                $recallType->setBySms(true);
            }

            if ($options['by_call']) {
                $recallType->setByCall(true);
            }

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
