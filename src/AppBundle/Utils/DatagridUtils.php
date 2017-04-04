<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.04.2017
 * Time: 18:36
 */

namespace AppBundle\Utils;

use AppBundle\Handler\FormHandler;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Paginator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class DatagridUtils
{

    const ITEMS_PER_PAGE = 15;

    /** @var Paginator */
    protected $paginator;

    /** @var  FormHandler */
    protected $formHandler;

    /** @var  \Twig_Environment */
    protected $twig;

    public function __construct(FormHandler $formHandler, Paginator $paginator, \Twig_Environment $twig)
    {
        $this->paginator = $paginator;
        $this->formHandler = $formHandler;
        $this->twig = $twig;
    }

    public function handleDatagrid(
        FormInterface $filterForm = null,
        Request $request,
        QueryBuilder $queryBuilder,
        callable $filterCallback = null,
        $gridTemplate
    ) {
        if ($filterForm) {
            $filterData = $this->handleFilter($filterForm, $request);

            if ($filterCallback && $filterData) {
                $filterCallback($queryBuilder, $filterData);
            }
        }

        $entities = $this->paginator->paginate(
            $queryBuilder,
            $request->get('page', 1),
            self::ITEMS_PER_PAGE
        );

        $result = array(
            'entities' => $entities,
        );
        
        if ($filterForm) {
            $result['filter'] = $filterForm->createView();
        }

        if ($request->isXmlHttpRequest()) {
            $response = new Response();
            $response->setContent($this->twig->render($gridTemplate, $result));

            return $response;
        }

        return $result;

    }

    protected function handleFilter(FormInterface $filterForm, Request $request)
    {
        /** @var Session $session */
        $filterName = $filterForm->getName();
        $filterData = null;

        if ($filterData = $request->get($filterName)) {
            $filterData = $this->formHandler->processForm($filterForm, $filterData, $request);
        }

        return $filterData;
    }
}
