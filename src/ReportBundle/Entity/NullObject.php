<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.05.2017
 * Time: 14:52
 */

namespace ReportBundle\Entity;

class NullObject
{

    public function __toString()
    {
        return 'Not specified';
    }

    public function getId()
    {
        return null;
    }

}
