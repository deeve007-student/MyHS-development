<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentCategory;
use AppBundle\Utils\FilterUtils;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Document controller.
 *
 * @Route("document")
 */
class DocumentController extends Controller
{
    /**
     * Lists all document entities.
     *
     * @Route("/", name="document_index")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->getRepository('AppBundle:Document')
            ->createQueryBuilder('p');
        $qb->leftJoin('p.category', 'pc');

        return $this->get('app.datagrid_utils')->handleDatagrid(
            $this->get('app.document_filter.form'),
            $request,
            $qb,
            function ($qb, $filterData) {
                FilterUtils::buildTextGreedyCondition(
                    $qb,
                    array(
                        'originFileName',
                    ),
                    $filterData['string']
                );

                if ($filterData['category']) {
                    $qb->andWhere($qb->expr()->in('pc.id', ':categories'))
                        ->setParameter('categories', $filterData['category']);
                }
            },
            '@App/Document/include/grid.html.twig'
        );
    }

    /**
     * Download document.
     *
     * @Route("/{id}/download", name="document_download")
     * @Method("GET")
     */
    public function downloadAction(Document $attachment)
    {
        $downloadHandler = $this->get('vich_uploader.download_handler');

        return $downloadHandler->downloadObject($attachment, 'file', null, $attachment->getFileName());
    }

    /**
     * Creates a new document entity.
     *
     * @Route("/new", name="document_create")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:Document:update.html.twig")
     */
    public function createAction(Request $request)
    {
        $document = $this->get('app.entity_factory')->createDocument();
        $result = $this->update($document);
        $em = $this->getDoctrine()->getManager();
        $form = $this->get('app.document.form');

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('categorySelector')->getData() == 'new') {
                $category = new DocumentCategory();
                $category->setDefaultCategory(false);
                $category->setName($form->get('newCategory')->getData());
                $em->persist($category);
            } else {
                $category = $em->getRepository('AppBundle:DocumentCategory')->find($form->get('categorySelector')->getData());
            }

            $document->setCategory($category);
            $em->flush();
        }

        return $result;
    }

    /**
     * Displays a form to edit an existing document entity.
     *
     * @Route("/{id}/update", name="document_update")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:Document:update.html.twig")
     */
    public function updateAction(Request $request, Document $document)
    {
        return $this->update($document);
    }

    /**
     * Deletes a document entity.
     *
     * @Route("/{id}/delete", name="document_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Document $document)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($document);
        $em->flush();

        $this->addFlash(
            'success',
            'app.document.message.deleted'
        );

        return $this->redirectToRoute('document_index');
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.document.form'),
            null,
            $entity,
            'app.document.message.created',
            'app.document.message.updated',
            'document_index'
        );
    }
}
