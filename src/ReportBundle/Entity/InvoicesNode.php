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
    protected $paymentsTotals;

    public function __construct($object = null)
    {
        parent::__construct($object);

        $this->payments = array();
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


}
