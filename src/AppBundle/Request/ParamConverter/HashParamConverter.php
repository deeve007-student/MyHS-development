<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 28.03.2017
 * Time: 23:34
 */

namespace AppBundle\Request\ParamConverter;

use AppBundle\Utils\Hasher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

class HashParamConverter extends DoctrineParamConverter
{

    /** @var ManagerRegistry */
    protected $registry;

    /** @var Hasher */
    protected $hasher;

    /** @var string */
    protected $class = '';

    /** @var  VarDumper */
    protected $dumper;

    public function __construct(ManagerRegistry $registry = null, Hasher $hasher)
    {
        parent::__construct($registry);
        $this->registry = $registry;
        $this->hasher = $hasher;
        $this->dumper = new VarDumper();
    }

    public function supports(ParamConverter $configuration)
    {
        if ($configuration->getClass()) {
            $em = $this->registry->getManagerForClass($configuration->getClass());
            $this->class = $em->getMetadataFactory()->getMetadataFor($configuration->getClass())->getName();
        }

        return parent::supports($configuration);
    }

    protected function getIdentifier(Request $request, $options, $name)
    {

        $id = parent::getIdentifier($request, $options, $name);

        /* Todo: investigate - do we need this multiple support?
        if ($id && array_key_exists('multiple', $options) && $options['multiple']) {
            $id = array_map([$this->hashids, 'decode'], array_filter(preg_split('/[\s,]+/', $id)));
            return $id;
        }
        */

        if (!is_object($id)) {
            return $this->hasher->decode($id, $this->class);
        }

        return false;
    }

}
