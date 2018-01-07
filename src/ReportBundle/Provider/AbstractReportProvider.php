<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.05.2017
 * Time: 14:36
 */

namespace ReportBundle\Provider;

use AppBundle\Utils\Formatter;
use AppBundle\Utils\Hasher;
use ReportBundle\Entity\Node;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Translation\Translator;
use UserBundle\Entity\User;
use Symfony\Component\Routing\Router;

abstract class AbstractReportProvider implements ReportProviderInterface
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Router */
    protected $router;

    /** @var  Formatter */
    protected $formatter;

    /** @var  Translator */
    protected $translator;

    /** @var  Hasher */
    protected $hasher;

    public function __construct(
        EntityManager $entityManager,
        Router $router,
        Formatter $formatter,
        Translator $translator,
        Hasher $hasher
    )
    {
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->formatter = $formatter;
        $this->translator = $translator;
    }

    /**
     * @param QueryBuilder $qb
     * @param $reportFormData
     * @return QueryBuilder
     */
    protected function bindFormData(QueryBuilder $qb, $reportFormData)
    {
        $alias = $qb->getRootAliases()[0];
        if ($reportFormData['company']) {
            $qb->where($alias . '.company = :company')
                ->setParameter('company', $reportFormData['company']);
        }
        return $qb;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param $fieldName
     * @param $entityClass
     * @return array
     */
    protected function getDistinctObjects(QueryBuilder $queryBuilder, $fieldName, $entityClass)
    {
        $queryBuilder = clone $queryBuilder;

        $queryBuilder->select($fieldName)
            ->distinct(true);
        $result = $queryBuilder->getQuery()->getResult();

        asort($result);

        foreach ($result as $row => $value) {
            $object = null;
            if ($value['id']) {
                $object = $this->entityManager->getRepository($entityClass)->find($value['id']);
            }
            $result[$row] = $object;
        }

        $this->sortObjects($result);

        return $result;
    }

    protected function sortObjects(&$objects)
    {
        uasort($objects, function ($a, $b) {
            if ($a instanceof User && $b instanceof User) {
                return strcmp($a->getLastName() . ' ' . $a->getFirstName(), $b->getLastName() . ' ' . $b->getFirstName());
            }
            if (is_object($a) && is_object($b)) {
                return strcmp((string)$a, (string)$b);
            }
            return $a > $b ? 1 : -1;
        });
    }

    /**
     * @param Node $node
     * @param $object
     */
    protected function setNodeObject(Node $node, $object)
    {
        $node->setObject($object);

    }

}
