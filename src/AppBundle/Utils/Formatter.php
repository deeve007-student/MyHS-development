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
        return $dateTime->format($this->getBackendDateFormat());
    }

    public function formatDateTime(\DateTime $dateTime)
    {
        return $dateTime->format($this->getDateTimeBackendFormat());
    }

    public function getBackendDateFormat()
    {
        return 'j M Y';
    }

    public function getDateTimeBackendFormat()
    {
        return 'j M Y g:i A';
    }

}
