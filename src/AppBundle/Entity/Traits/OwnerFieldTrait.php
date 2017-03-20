<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 20.03.2017
 * Time: 8:36
 */

namespace AppBundle\Entity\Traits;

use UserBundle\Entity\User;

trait OwnerFieldTrait
{

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_user_id", referencedColumnName="id", nullable=true)
     */
    protected $owner;

    /**
     * @param User|null $owner
     * @return $this
     */
    public function setOwner(User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

}
