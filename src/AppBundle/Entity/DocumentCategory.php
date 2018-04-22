<?php
/**
 * Created by PhpStorm.
 * User: stepa
 * Date: 22.04.2018
 * Time: 17:25
 */

namespace AppBundle\Entity;


use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="document_category")
 * @ORM\HasLifecycleCallbacks()
 */
class DocumentCategory
{
    use CreatedUpdatedTrait;
    use OwnerFieldTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $defaultCategory;

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @return DocumentCategory
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDefaultCategory()
    {
        return $this->defaultCategory;
    }

    /**
     * @param bool $defaultCategory
     * @return DocumentCategory
     */
    public function setDefaultCategory($defaultCategory)
    {
        $this->defaultCategory = $defaultCategory;
        return $this;
    }

}