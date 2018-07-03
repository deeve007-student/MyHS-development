<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 29.03.2017
 * Time: 9:48
 */

namespace AppBundle\Utils;

use Doctrine\Common\Util\ClassUtils;
use Hashids\Hashids;
use Symfony\Component\HttpFoundation\Session\Session;

class Hasher
{

    /** @var  string */
    protected $salt;

    /** @var  string */
    protected $padding;

    /** @var  Session */
    protected $session;

    public function __construct($salt, $padding, Session $session)
    {
        $this->salt = $salt;
        $this->padding = $padding;
        $this->session = $session;
    }

    protected function getHashids($additionalSalt)
    {
        return new Hashids($this->salt.$additionalSalt, $this->padding);
    }

    protected function encode($str, $additionalSalt = '')
    {
        return $this->getHashids($additionalSalt)->encode($str);
    }

    public function encodeObject($object, $className = null)
    {
        if (is_object($object)) {
            if (!$className) {
                $className = ClassUtils::getClass($object);
                //$className = ClassUtils::getParentClass($object);
            }
            $encodedString = $this->encode($object->getId(), $className);
            return $encodedString;
        }

        throw new \Exception('Only object allowed to pass. Passed "'.$object.'"');
    }

    public function decode($str, $additionalSalt = '')
    {
        $hashid = $this->getHashids($additionalSalt);
        $decoded = $hashid->decode($str);

        if (!isset($decoded[0])) {
            throw new \Exception('Hash decode error');
        }

        return $decoded[0];
    }

    /**
     * @return \Closure
     */
    public function choiceValueCallback()
    {
        return function ($object = null) {
            if ($object) {
                return $this->encodeObject($object);
            }

            return null;
        };
    }

}
