<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 29.03.2017
 * Time: 0:26
 */

namespace AppBundle\Twig;

use AppBundle\Utils\Hasher;

class HasherExtension extends \Twig_Extension
{

    /** @var  Hasher */
    protected $hasher;

    public function setHasher(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('app_hash', array($this, 'hashFunction')),
        );
    }

    public function hashFunction($object, $class = null)
    {
        if ($class) {
            return $this->hasher->encodeObject($object, $class);
        }
        return $this->hasher->encodeObject($object);
    }
}
