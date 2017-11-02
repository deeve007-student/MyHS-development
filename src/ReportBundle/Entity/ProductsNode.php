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

class ProductsNode extends Node
{

    /** @var  integer */
    protected $quantitySold;

    public function __construct($object = null)
    {
        parent::__construct($object);
        $this->setQuantitySold(0);
    }

    /**
     * @return int
     */
    public function getQuantitySold()
    {
        return $this->quantitySold;
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



}
