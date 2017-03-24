<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.03.2017
 * Time: 17:59
 */


namespace AppBundle\Utils;

use AppBundle\Handler\FormHandler;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class FilterUtils {

    /** @var Session  */
    protected $session;

    /** @var  FormHandler */
    protected $formHandler;

    public function __construct(Session $session, FormHandler $formHandler)
    {
        $this->session = $session;
        $this->formHandler = $formHandler;
    }

    /**
     * @param FormInterface $filterForm
     * @param Request $request
     */
    public function handleFilter(FormInterface $filterForm, Request $request)
    {
        /** @var Session $session */
        $filterName = $filterForm->getName();

        if ($filterData = $request->get($filterName)) {
            $request->query->remove('page');
            $filterData = $this->formHandler->processForm($filterForm, $filterData, $request);
            $this->session->set($filterName, $filterData);
        }

        if (!$filterForm->isSubmitted()) {
            $filterForm->setData($this->session->get($filterName, array()));
        }
    }

    /**
     * @param FormInterface $filterForm
     * @param Request $request
     * @return null|array
     */
    public function getFilterData(FormInterface $filterForm, Request $request)
    {

        $this->handleFilter($filterForm, $request);
        $filterName = $filterForm->getName();

        if ($this->session->has($filterName)) {
            return $this->session->get($filterName, array());
        }

        return null;
    }

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
