<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 05.08.2017
 * Time: 21:34
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\CommunicationType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCommunicationTypesData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $types = array(
            'Email' => array(
                'send_translation' => 'send_email',
                'by_email' => true,
                'by_sms' => false,
                'by_call' => false,
            ),
            'SMS' => array(
                'send_translation' => 'send_sms',
                'by_email' => false,
                'by_sms' => true,
                'by_call' => false,
            )
        );

        foreach ($types as $recallTypeName => $options) {
            $recallType = new CommunicationType();
            $recallType->setName($recallTypeName)
                ->setTranslation($options['send_translation']);

            if ($options['by_email']) {
                $recallType->setByEmail(true);
            }

            if ($options['by_sms']) {
                $recallType->setBySms(true);
            }

            $manager->persist($recallType);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 25;
    }
}
