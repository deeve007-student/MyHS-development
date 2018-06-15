<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 06.06.2017
 * Time: 11:58
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="event_unavailable_block")
 * @ORM\HasLifecycleCallbacks()
 */
class UnavailableBlock extends Event
{

    public function __toString()
    {
        return $this->getDescription() ? $this->getDescription() : '';
    }

}
