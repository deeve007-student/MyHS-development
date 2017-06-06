<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 06.06.2017
 * Time: 11:46
 */

namespace AppBundle\Utils;

class EventUtils
{

    public function getInterval()
    {
        return '15';
    }

    public function getDayStart()
    {
        return '08:00';
    }

    public function getDayEnd()
    {
        return '20:00';
    }

    public function getBusinessDayStart()
    {
        return '10:00';
    }

    public function getBusinessDayEnd()
    {
        return '18:00';
    }

}
