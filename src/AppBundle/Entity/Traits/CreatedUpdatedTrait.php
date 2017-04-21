<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin
 * Date: 08.10.2015
 * Time: 14:05
 */

namespace AppBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait CreatedUpdatedTrait
{

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /** @var  bool */
    protected $overrideDates = false;

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function beforeSave()
    {
        if (!$this->overrideDates || ($this->overrideDates && !$this->getCreatedAt())) {
            $this->createdAt = new \DateTime('now', new \DateTimeZone('UTC'));
        }
        $this->beforeUpdate();
    }

    /**
     * @ORM\PreUpdate
     */
    public function beforeUpdate()
    {
        if (!$this->overrideDates || ($this->overrideDates && !$this->getUpdatedAt())) {
            $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
        }
        $this->overrideDates(true);
    }

    public function overrideDates($reset = false)
    {
        if ($reset) {
            $this->overrideDates = false;
        } else {
            $this->overrideDates = true;
        }
    }

}
