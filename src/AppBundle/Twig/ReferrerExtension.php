<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 20.04.2017
 * Time: 12:14
 */

namespace AppBundle\Twig;

use AppBundle\Utils\Hasher;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;

class ReferrerExtension extends \Twig_Extension
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Router */
    protected $router;

    /** @var  Hasher */
    protected $hasher;

    public function setDependencies(EntityManager $entityManager, Router $router, Hasher $hasher)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->hasher = $hasher;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('app_referrer', array($this, 'referrerFilter')),
        );
    }

    public function referrerFilter($value)
    {
        if ($patient = $this->entityManager->getRepository('AppBundle:Patient')->find((int)$value)) {
            $url = $this->router->getGenerator()->generate(
                'patient_view',
                array('id' => $this->hasher->encodeObject($patient))
            );

            return '<a href="'.$url.'">'.(string)$patient.'</a>';
        }

        return $value;
    }
}
