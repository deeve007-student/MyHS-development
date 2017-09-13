<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.09.2017
 * Time: 13:11
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Country;
use AppBundle\Entity\SmsCost;
use Doctrine\ORM\EntityManager;
use Symfony\Component\VarDumper\VarDumper;
use Twilio\Rest\Client;

class TwilioUtils
{

    /** @var  Client */
    protected $client;

    /** @var  EntityManager */
    protected $entityManager;

    public function __construct(Client $client, EntityManager $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Country $country
     * @return SmsCost
     */
    public function getAverageSmsCost(Country $country)
    {
        if ($cost = $this->entityManager->getRepository('AppBundle:SmsCost')->findOneBy(
            array(
                'country' => $country,
                'date' => new \DateTime(),
            )
        )) {
            return $cost;
        }

        $costData = $this->fetchAverageSmsCost($country->getIsoCode());

        $cost = new SmsCost();
        $cost->setDate(new \DateTime())
            ->setCountry($country)
            ->setInboundCost($costData['inbound'])
            ->setOutboundCost($costData['outbound']);

        $this->entityManager->persist($cost);
        $this->entityManager->flush();

        return $cost;
    }

    protected function fetchAverageSmsCost($isoCountry)
    {
        if ($isoCountry == "") {
            throw new \Exception('Cannot fetch SMS cost with no country specified');
        }

        $data = $this->client->pricing->messaging->countries($isoCountry)->fetch();
        $result = array();
        $types = array('inbound', 'outbound');

        foreach ($types as $type) {
            $sum = 0;
            $count = 0;
            $property = $type . 'SmsPrices';

            if (count($data->$property)) {
                foreach ($data->$property as $priceData) {
                    if (isset($priceData['prices'])) {
                        foreach ($priceData['prices'] as $priceInnerData) {
                            $sum += $priceInnerData['current_price'];
                            $count++;
                        }
                    } else {
                        $sum += $priceData['current_price'];
                        $count++;
                    }
                }
                $sum = $sum / $count;
            }
            $result[$type] = $sum;
        }

        return $result;
    }

}
