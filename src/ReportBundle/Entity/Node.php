<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.05.2017
 * Time: 14:52
 */

namespace ReportBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Node
{
    /** @var  string */
    protected $id;

    /** @var  string */
    protected $name;

    /** @var  string */
    protected $route;

    /** @var  string */
    protected $acl;

    /** @var  Node[]|ArrayCollection */
    protected $children;

    /** @var  Node */
    protected $parent;

    /** @var  object */
    protected $object;

    /** @var  NodeValue[]|ArrayCollection */
    protected $values;

    public function __construct($object = null)
    {
        if ($object) {
            $this->setObject($object);
        }
        $this->id = uniqid('_');
        $this->children = new ArrayCollection();
        $this->values = new ArrayCollection();
    }

    public function getId()
    {
        $resultId = '';
        if ($parentNode = $this->getParent()) {
            $resultId .= $parentNode->getId();
        }
        return $resultId . $this->id;
    }

    /**
     * @return bool
     */
    public function isDeepest()
    {
        if (!$this->getChildren()->count()) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return !$this->isDeepest();
    }

    /**
     * @return bool
     */
    public function isRoot()
    {
        if (!$this->getParent()) {
            return true;
        }
        return false;
    }

    /**
     * @return integer
     */
    public function getLevel()
    {
        if ($this->isRoot()) {
            return 0;
        }
        return $this->getParent()->getLevel() + 1;
    }

    public function setName($val)
    {
        $this->name = $val;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setRoute($val)
    {
        $this->route = $val;
        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setAcl($val)
    {
        $this->acl = $val;
        return $this;
    }

    public function getAcl()
    {
        return $this->acl;
    }

    public function setObject($val, $name = null)
    {
        $this->object = $val;

        if ($name) {
            $this->setName($name);
        } else {
            if ((string)$val) {
                $this->setName((string)$val);
            }
        }

        return $this;
    }

    public function getObject()
    {
        return $this->object;
    }

    private function setParent($val)
    {
        $this->parent = $val;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function addChild(Node $node)
    {
        $this->children->add($node);
        $node->setParent($this);
        return $this;
    }

    public function removeChild(Node $node)
    {
        $this->children->removeElement($node);
        return $this;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function getValueByName($name)
    {
        foreach ($this->getValues() as $value) {
            if ($value->getName() == $name) {
                return $value;
            }
        }
        return false;
    }

    public function getChildrenByName($name)
    {
        foreach ($this->getChildren() as $children) {
            if ($children->getName() == $name) {
                return $children;
            }
        }
        return false;
    }

    public function addValue(NodeValue $value)
    {
        $this->values->add($value);
        $value->setParent($this);
        return $this;
    }

    public function removeValue(NodeValue $value)
    {
        $this->children->removeElement($value);
        $value->setParent(null);
        return $this;
    }

    public function getDeepestLevel()
    {
        if ($this->getChildren()->count() > 0) {
            $level = $this->getLevel();
            foreach ($this->getChildren() as $node) {
                if ($node->getLevel() > $level) {
                    $level = $node->getDeepestLevel();
                }
            }
            return $level;
        }
        return $this->getLevel();
    }

}
