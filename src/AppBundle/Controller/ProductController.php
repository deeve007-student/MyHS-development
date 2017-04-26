<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:14
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Utils\FilterUtils;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{

    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('AppBundle:Product')->createQueryBuilder('p');

        return $this->get('app.datagrid_utils')->handleDatagrid(
            $this->get('app.string_filter.form'),
            $request,
            $qb,
            function ($qb, $filterData) {
                FilterUtils::buildTextGreedyCondition(
                    $qb,
                    array(
                        'name',
                        'price',
                        'supplier',
                        'code',
                    ),
                    $filterData['string']
                );
            },
            '@App/Product/include/grid.html.twig'
        );
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_create")
     * @Method({"GET", "POST"})
     * @Template("@App/Product/update.html.twig")
     */
    public function createAction(Request $request)
    {
        $product = $this->get('app.entity_factory')->createProduct();

        return $this->update($product);
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/update", name="product_update")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Product $product)
    {
        return $this->update($product);
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}/delete", name="product_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $this->addFlash(
            'success',
            'app.product.message.deleted'
        );

        return $this->redirectToRoute('product_index');
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.product.form'),
            null,
            $entity,
            'app.product.message.created',
            'app.product.message.updated',
            'product_index',
            $this->get('app.hasher')->encodeObject($entity)
        );
    }

    /**
     * Returns product price.
     *
     * @Route("/price/{id}", name="product_price_view", options={"expose"=true})
     * @Method("POST")
     */
    public function viewPriceAction(Product $product)
    {
        return new JsonResponse(
            json_encode(
                array(
                    'price' => trim(
                        $product->getPrice()
                    ),
                )
            )
        );
    }
}
