<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 22.11.2016
 * Time: 15:24
 */

namespace AppBundle\Handler;

use AppBundle\Utils\Hasher;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    /** @var  Hasher */
    protected $hasher;

    /** @var  \Twig_Environment */
    protected $twig;

    public function __construct(
        EntityManager $entityManager,
        FormHandler $formHandler,
        RequestHandler $requestHandler,
        RequestStack $requestStack,
        Router $router,
        Session $session,
        Hasher $hasher,
        \Twig_Environment $twig
    )
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
        $this->request = $requestStack->getCurrentRequest();
        $this->requestHandler = $requestHandler;
        $this->formHandler = $formHandler;
        $this->router = $router;
        $this->session = $session;
        $this->hasher = $hasher;
        $this->twig = $twig;
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

    /**
     * @param FormInterface $form
     * @param null $formTemplate
     * @param $data
     * @param null $successCreateMessage
     * @param null $successUpdateMessage
     * @param null $redirectRoute
     * @param null $routeIdParam
     * @param callable|null $saveCallback
     * @param array $additionalData
     * @return array|JsonResponse|RedirectResponse
     */
    public function handleCreateOrUpdate(
        FormInterface $form,
        $formTemplate = null,
        $data,
        $successCreateMessage = null,
        $successUpdateMessage = null,
        $redirectRoute = null,
        $routeIdParam = null,
        callable $saveCallback = null,
        $additionalData = array()
    )
    {

        // If form and entity are valid

        if ($entity = $this->formHandler->processForm($form, $data, $this->request)) {

            // Add notifications to flashbag regular requests (not AJAX)

            if ($entity->getId() && $successUpdateMessage) {

                if ($this->request->isXmlHttpRequest()) {
                    $ajaxResult['message'] = $successUpdateMessage;
                } else {
                    $this->session->getFlashBag()->add('success', $successUpdateMessage);
                }

            } elseif (!$entity->getId() && $successCreateMessage) {

                if ($this->request->isXmlHttpRequest()) {
                    $ajaxResult['message'] = $successCreateMessage;
                } else {
                    $this->session->getFlashBag()->add('success', $successCreateMessage);
                }

            }

            // Try to save an entity

            $this->saveEntity($entity);

            // If regular request - process redirect or callback
            // If AJAX - render form template

            if ($this->request->isXmlHttpRequest()) {
                $ajaxResult['error'] = 0;
                $ajaxResult['form'] = $this->twig->render(
                    $formTemplate,
                    array(
                        'entity' => $entity,
                        'form' => $form->createView(),
                    )
                );

                $ajaxResult['data'] = $additionalData;

                return new JsonResponse(json_encode($ajaxResult));
            } else {
                if ($redirectRoute) {
                    return $this->processRoute($redirectRoute, $routeIdParam, $entity);
                } elseif (is_callable($saveCallback)) {
                    return $saveCallback($entity);
                }

            }

        }

        // If regular request - return form view and data
        // If AJAX - return rendered form template and data

        if ($this->request->isXmlHttpRequest()) {
            $ajaxResult = array(
                'error' => 1,
                'message' => 'app.form.not_valid',
                'form' => $this->twig->render(
                    $formTemplate,
                    array(
                        'entity' => $data,
                        'form' => $form->createView(),
                    )
                ),
            );

            return new JsonResponse(json_encode($ajaxResult));
        } else {
            return array(
                'entity' => $data,
                'form' => $form->createView(),
            );
        }
    }

    protected function processRoute(
        $redirectRoute,
        $routeIdParam = null,
        $entity
    )
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
            // $routeParams['id'] = $entity->getId();
            $routeParams['id'] = $this->hasher->encodeObject($entity);
        }

        $url = $this->router->generate($redirectRoute, $routeParams);

        return new RedirectResponse($url);
    }

    protected function saveEntity(
        $entity
    )
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    protected function deleteEntity(
        $entity
    )
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}
