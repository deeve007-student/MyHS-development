<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 22.08.2017
 * Time: 16:58
 */

namespace AppBundle\EventListener;

use AppBundle\Utils\DateTimeUtils;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\VarDumper\VarDumper;

class DateTimeListener
{

    /** @var  TokenStorage */
    protected $tokenStorage;

    /** @var  AnnotationReader */
    protected $docReader;

    /** @var  DateTimeUtils */
    protected $dateTimeUtils;

    public function __construct(TokenStorage $tokenStorage, DateTimeUtils $dateTimeUtils)
    {
        $this->tokenStorage = $tokenStorage;
        $this->dateTimeUtils = $dateTimeUtils;
        $this->docReader = new AnnotationReader();
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $reader = $this->docReader;
        $entity = $args->getEntity();
        $reflect = new \ReflectionClass($entity);

        $props = $reflect->getProperties();

        foreach ($props as $prop) {

            $docInfos = $reader->getPropertyAnnotations($prop);

            foreach ($docInfos as $info) {

                if (!$info instanceof \Doctrine\ORM\Mapping\Column) continue;

                if ($info->type !== "datetime") continue;

                $getDateMethod = 'get' . ucfirst($prop->getName());

                $val = $entity->{$getDateMethod}();

                $user = null;
                if ($this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getUser()) {
                    $user = $this->tokenStorage->getToken()->getUser();
                }

                if ($val && $user && is_object($user)) {
                    $tzIdentifier = $this->tokenStorage->getToken()->getUser()->getTimezone();
                    $tz = new \DateTimeZone($tzIdentifier);
                    $val->setTimeZone($tz);
                }

            }
        }
    }

}
