<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:14
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAll();

        return array(
            'products' => $products,
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
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="product_view")
     * @Method("GET")
     * @Template()
     */
    public function viewAction(Product $product)
    {
        return array(
            'entity' => $product,
        );
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

        return $this->redirectToRoute('product_index');
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.product.form'),
            $entity,
            'product_view',
            $entity->getId()
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
