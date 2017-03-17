<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 22.11.2016
 * Time: 15:24
 */

namespace AppBundle\Handler;

use Doctrine\ORM\EntityManager;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Router;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\VarDumper\VarDumper;

class EntityActionHandler
{

    /** @var  FormHandler */
    protected $formHandler;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  RequestHandler */
    protected $requestHandler;

    /** @var  Request */
    protected $request;

    /** @var  RequestStack */
    protected $requestStack;

    /** @var  Router */
    protected $router;

    /** @var  Session */
    protected $session;

    public function __construct(
        EntityManager $entityManager,
        FormHandler $formHandler,
        RequestHandler $requestHandler,
        RequestStack $requestStack,
        Router $router,
        Session $session
    ) {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
        $this->request = $requestStack->getCurrentRequest();
        $this->requestHandler = $requestHandler;
        $this->formHandler = $formHandler;
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * @param $form
     * @param $entityClass
     * @param null $entityId To handle PUT action
     * @return View
     */
    public function handleRestCreateOrUpdate(FormInterface $form, $entityClass, $entityId = null)
    {

        $entity = new $entityClass();
        if ($entityId && $entityFetched = $this->entityManager->find($entityClass, $entityId)) {
            $entity = $entityFetched;
        }

        $entityId = $entity->getId();

        $this->requestHandler->fixRequestAttributes($form, $this->request);

        $entityProcessed = $this->formHandler->processForm($form, $entity, $this->request);

        if ($entityProcessed) {
            $this->saveEntity($entityProcessed);
            $responseCode = $entity->getId() == $entityId ? 200 : 201;

            return View::create($entityProcessed, $responseCode);
        } else {
            return View::create($form, 400);
        }
    }

    public function handleRestGet($entityClass, $entityId)
    {
        if (!$project = $this->entityManager->getRepository($entityClass)->find($entityId)) {
            throw new NotFoundHttpException();
        }

        return $project;
    }

    public function handleRestDelete($entityClass, $entityId)
    {
        if (!$entity = $this->entityManager->getRepository($entityClass)->find($entityId)) {
            throw new NotFoundHttpException();
        }

        $this->deleteEntity($entity);

        return View::create(null, 204);
    }

    public function handleCreateOrUpdate(
        FormInterface $form,
        $data,
        $successCreateMessage = null,
        $successUpdateMessage = null,
        $redirectRoute = null,
        $routeIdParam = null,
        callable $saveCallback = null
    ) {
        if ($entity = $this->formHandler->processForm($form, $data, $this->request)) {

            if ($entity->getId() && $successUpdateMessage) {
                $this->session->getFlashBag()->add('success', $successUpdateMessage);
            } elseif (!$entity->getId() && $successCreateMessage) {
                $this->session->getFlashBag()->add('success', $successCreateMessage);
            }

            $this->saveEntity($entity);

            if ($redirectRoute) {
                return $this->processRoute($redirectRoute, $routeIdParam, $entity);
            } elseif (is_callable($saveCallback)) {
                return $saveCallback($entity);
            }

        }

        return array(
            'entity' => $data,
            'form' => $form->createView(),
        );
    }

    protected function processRoute($redirectRoute, $routeIdParam = null, $entity)
    {
        $routeParams = array();
        if ($routeIdParam) {
            $routeParams['id'] = $routeIdParam;
        } elseif (!$routeIdParam &&
            in_array(
                'id',
                $this->router->getRouteCollection()->get($redirectRoute)->compile()->getPathVariables()
            )
        ) {
            $routeParams['id'] = $entity->getId();
        }

        $url = $this->router->generate($redirectRoute, $routeParams);

        return new RedirectResponse($url);
    }

    protected function saveEntity($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    protected function deleteEntity($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}
