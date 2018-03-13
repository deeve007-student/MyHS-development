<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 12.03.18
 * Time: 16:24
 */

namespace AppBundle\Entity\Traits;

trait InvoiceRefundTrait
{

    public function getItem()
    {
        return $this->item;
    }

    public function setItem($item)
    {
        $this->item = $item;
        return $this;
    }

}
