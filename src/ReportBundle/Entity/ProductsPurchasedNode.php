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

class ProductsPurchasedNode extends Node
{

    /** @var  integer */
    protected $quantitySold;

    /** @var  string */
    protected $dateSold;

    /** @var  string */
    protected $code;

    public function __construct($object = null)
    {
        parent::__construct($object);
        $this->setQuantitySold(0);
    }

    public function getQuantitySold()
    {
        if ($this->getChildren()->count()) {
            $sum = 0;
            foreach ($this->getChildren() as $node) {
                $sum += $node->getQuantitySold();
            }
            return $sum;
        } else {
            return $this->quantitySold;
        }
    }

    /**
     * @param int $quantitySold
     */
    public function setQuantitySold($quantitySold)
    {
        $this->quantitySold = $quantitySold;
    }

    /**
     * @param int $quantitySold
     */
    public function addQuantitySold($quantitySold)
    {
        $this->quantitySold += $quantitySold;
    }

    /**
     * @return string
     */
    public function getDateSold()
    {
        return $this->dateSold;
    }

    /**
     * @param string $dateSold
     */
    public function setDateSold($dateSold)
    {
        $this->dateSold = $dateSold;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        if ($this->getChildren()->count()) {
            return $this->getChildren()->first()->getCode();
        } else {
            return $this->code;
        }
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }


}
