<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.03.2017
 * Time: 17:59
 */


namespace AppBundle\Utils;

use Doctrine\ORM\QueryBuilder;

class FilterUtils {

    static function buildTextGreedyCondition(QueryBuilder $queryBuilder, array $fields, $string)
    {
        $rootEntityAlias = $queryBuilder->getDQLPart('from')[0]->getAlias();
        $stringParts = explode(' ', preg_replace('/\s+/', ' ', trim($string)));
        $andCond = $queryBuilder->expr()->andX();

        $stringPartCounter = 0;
        foreach ($stringParts as $stringPart) {
            $paramName = ':stringPart'.$stringPartCounter++;
            $orConds = $queryBuilder->expr()->orX();

            foreach ($fields as $field) {
                $orConds->add(
                    $queryBuilder->expr()->like($rootEntityAlias.'.'.$field, $paramName)
                );
            }
            $andCond->add($orConds);
            $queryBuilder->setParameter($paramName, '%'.$stringPart.'%');
        }

        $queryBuilder->andWhere($andCond);

        return $queryBuilder;
    }

}
