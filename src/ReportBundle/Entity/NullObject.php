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

    /** @var string */
    protected $name;

    public function __toString()
    {
        return $this->getName() ? $this->getName() : 'Not specified';
    }

    public function getId()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return NullObject
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


}
