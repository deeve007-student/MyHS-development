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

    /** @var  array */
    protected $refundsTotals;

    /** @var double */
    protected $outstanding;

    /** @var double */
    protected $refunded;

    public function __construct($object = null)
    {
        parent::__construct($object);

        $this->refunds = array();
        $this->payments = array();
        $this->paymentsTotals = array();
        $this->refundsTotals = array();
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

    /**
     * @return array
     */
    public function getPaymentsTotalsWithRefunds()
    {
        $data = array();
        foreach ($this->getPaymentsTotals() as $name => $amount) {
            if (isset($this->refundsTotals[$name])) {
                $data[$name] = $amount - $this->refundsTotals[$name];
            } else {
                $data[$name] = $amount;
            }
        }
        return $data;
    }

    public function getPaymentsTotalsSum($withRefunded = true)
    {
        $sum = 0;
        foreach ($this->getPaymentsTotals() as $total) {
            $sum += $total;
        }
        if ($withRefunded) {
            return $sum - $this->getRefunded();
        }
        return $sum;
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

    /**
     * @return array
     * @return InvoicesNode
     */
    public function getRefundsTotals()
    {
        return $this->refundsTotals;
        return $this;
    }

    /**
     * @param array $refundsTotals
     */
    public function setRefundsTotals($refundsTotals)
    {
        $this->refundsTotals = $refundsTotals;
    }

}
