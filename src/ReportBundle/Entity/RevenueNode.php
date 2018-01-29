<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.05.2017
 * Time: 14:58
 */

namespace ReportBundle\Entity;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Recall;
use Doctrine\Common\Collections\ArrayCollection;

class RevenueNode extends Node
{

    /** @var  double */
    protected $productsBilled = 0;

    /** @var  double */
    protected $servicesBilled = 0;

    /** @var  double */
    protected $productsPaid = 0;

    /** @var  double */
    protected $servicesPaid = 0;

    /** @var  double */
    protected $revenue = 0;

    /** @var  double */
    protected $nonAssignedPaid = 0;

    /** @var  Patient[]|ArrayCollection */
    protected $clients;

    public function __construct($object = null)
    {
        parent::__construct($object);
        $this->clients = new ArrayCollection();
    }

    /**
     * @return Patient[]|ArrayCollection
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param Patient $client
     */
    public function addClient($client)
    {
        $this->clients->add($client);
    }

    /**
     * @return float
     */
    public function getProductsBilled()
    {
        return $this->productsBilled;
    }

    /**
     * @param float $productsBilled
     */
    public function setProductsBilled($productsBilled)
    {
        $this->productsBilled = $productsBilled;
    }

    /**
     * @return float
     */
    public function getServicesBilled()
    {
        return $this->servicesBilled;
    }

    /**
     * @param float $servicesBilled
     */
    public function setServicesBilled($servicesBilled)
    {
        $this->servicesBilled = $servicesBilled;
    }

    /**
     * @return float
     */
    public function getProductsPaid()
    {
        return $this->productsPaid;
    }

    /**
     * @param float $productsPaid
     */
    public function setProductsPaid($productsPaid)
    {
        $this->productsPaid = $productsPaid;
    }

    /**
     * @return float
     */
    public function getServicesPaid()
    {
        return $this->servicesPaid;
    }

    /**
     * @param float $servicesPaid
     */
    public function setServicesPaid($servicesPaid)
    {
        $this->servicesPaid = $servicesPaid;
    }

    /**
     * @return float
     */
    public function getRevenue()
    {
        return $this->revenue;
    }

    /**
     * @param float $revenue
     */
    public function setRevenue($revenue)
    {
        $this->revenue = $revenue;
    }

    /**
     * @return float
     */
    public function getNonAssignedPaid()
    {

        if ($this->getChildren()->count()) {
            $sum = 0;
            foreach ($this->getChildren() as $node) {
                $sum += $node->getNonAssignedPaid();
            }
            return $sum;
        }
        return $this->nonAssignedPaid;
    }

    /**
     * @param float $nonAssignedPaid
     * @return RevenueNode
     */
    public function setNonAssignedPaid($nonAssignedPaid)
    {
        $this->nonAssignedPaid = $nonAssignedPaid;
        return $this;
    }

    /**
     * @param float $nonAssignedPaid
     * @return RevenueNode
     */
    public function addNonAssignedPaid($nonAssignedPaid)
    {
        $this->nonAssignedPaid += $nonAssignedPaid;
        return $this;
    }

}
