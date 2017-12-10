<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 10.12.17
 * Time: 15:06
 */

namespace AppBundle\Twig;

class ClassExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getClass', array($this, 'getClass')),
        );
    }

    public function getClass($object)
    {
        return get_class($object);
    }
}
