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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function __construct(
        EntityManager $entityManager,
        FormHandler $formHandler,
        RequestHandler $requestHandler,
        RequestStack $requestStack
    ) {
        $this->entityManager = $entityManager;
        $this->request = $requestStack->getCurrentRequest();
        $this->requestHandler = $requestHandler;
        $this->formHandler = $formHandler;
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

    public function handleCreateOrUpdate(FormInterface $form, $data)
    {
        if ($entity = $this->formHandler->processForm($form, $data, $this->request)) {

            $this->saveEntity($entity);

            return array(
                'entity' => $entity,
                'form' => $form->createView(),
            );
        }

        return array(
            'entity' => $data,
            'form' => $form->createView(),
        );
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