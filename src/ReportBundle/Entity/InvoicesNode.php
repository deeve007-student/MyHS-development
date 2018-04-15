<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.05.2017
 * Time: 14:58
 */

namespace ReportBundle\Entity;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Recall;

class InvoicesNode extends Node
{

    /** @var  array */
    protected $payments;

    /** @var  array */
    protected $refunds;

    /** @var  array */
    protected $paymentsTotals;

    /** @var double */
    protected $outstanding;

    /** @var double */
    protected $refunded;

    public function __construct($object = null)
    {
        parent::__construct($object);

        $this->payments = array();
    }

    /**
     * @return array
     */
    public function getRefunds()
    {
        return $this->refunds;
    }

    /**
     * @param array $refunds
     * @return InvoicesNode
     */
    public function setRefunds($refunds)
    {
        $this->refunds = $refunds;
        return $this;
    }

    /**
     * @return array
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @param array $payments
     */
    public function setPayments($payments)
    {
        $this->payments = $payments;
    }

    /**
     * @return array
     */
    public function getPaymentsTotals()
    {
        return $this->paymentsTotals;
    }

    public function getPaymentsTotalsSum()
    {
        $sum = 0;
        foreach ($this->getPaymentsTotals() as $total) {
            $sum += $total;
        }
        return $sum - $this->getRefunded();
    }

    /**
     * @param array $paymentsTotals
     * @return InvoicesNode
     */
    public function setPaymentsTotals($paymentsTotals)
    {
        $this->paymentsTotals = $paymentsTotals;
        return $this;
    }

    /**
     * @return float
     */
    public function getOutstanding()
    {
        return $this->outstanding;
    }

    /**
     * @param float $outstanding
     * @return InvoicesNode
     */
    public function addOutstanding($outstanding)
    {
        $this->outstanding += $outstanding;
        return $this;
    }

    /**
     * @return float
     */
    public function getRefunded()
    {
        return $this->refunded;
    }

    /**
     * @param float $refunded
     * @return InvoicesNode
     */
    public function addRefunded($refunded)
    {
        $this->refunded += $refunded;
        return $this;
    }

}
