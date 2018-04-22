<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DocumentCategory;
use AppBundle\Entity\DocumentCategoryCategory;
use AppBundle\Utils\FilterUtils;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * DocumentCategory controller.
 *
 * @Route("document-category")
 */
class DocumentCategoryController extends Controller
{
    /**
     * Lists all document entities.
     *
     * @Route("/", name="document_category_index", options={"expose"=true})
     * @Method({"GET","POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->getRepository('AppBundle:DocumentCategory')
            ->createQueryBuilder('p')
            ->andWhere('p.defaultCategory != :true')
            ->setParameter('true', true);

        return $this->get('app.datagrid_utils')->handleDatagrid(
            $this->get('app.string_filter.form'),
            $request,
            $qb,
            function ($qb, $filterData) {
                FilterUtils::buildTextGreedyCondition(
                    $qb,
                    array(
                        'name',
                    ),
                    $filterData['string']
                );
            },
            '@App/DocumentCategory/include/grid.html.twig'
        );
    }

    /**
     * Displays a form to edit an existing document entity.
     *
     * @Route("/{id}/update", name="document_category_update")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:DocumentCategory:update.html.twig")
     */
    public function updateAction(Request $request, DocumentCategory $documentCategory)
    {
        return $this->update($documentCategory);
    }

    /**
     * Deletes a document entity.
     *
     * @Route("/{id}/delete", name="document_category_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, DocumentCategory $documentCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($documentCategory);
        $em->flush();

        $this->addFlash(
            'success',
            'app.document_category_message.deleted'
        );

        return $this->redirectToRoute('practicioner_settings_index');
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.document_category.form'),
            null,
            $entity,
            'app.document_category_message.created',
            'app.document_category_message.updated',
            'practicioner_settings_index'
        );
    }
}
