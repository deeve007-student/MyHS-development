<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 09.06.2017
 * Time: 11:33
 */

namespace ReportBundle\Entity;

class NodeValue
{

    /** @var  bool */
    protected $hidden;

    protected $name;

    /** @var  Node */
    protected $parent;

    public function __construct()
    {
        $this->setHidden(false);
    }

    /**
     * @param $hidden bool
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * @return bool
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Node $val
     * @return $this
     */
    public function setParent(Node $val)
    {
        $this->parent = $val;
        return $this;
    }

    /**
     * @return Node
     */
    public function getParent()
    {
        return $this->parent;
    }

}
