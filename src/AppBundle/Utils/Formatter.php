<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 22.03.2017
 * Time: 11:59
 */

namespace AppBundle\Utils;

class Formatter
{

    public function formatDate(\DateTime $dateTime)
    {
        return $dateTime->format('d M Y');
    }

}
