<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:14
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Treatment;
use AppBundle\Utils\FilterUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Treatment controller.
 *
 * @Route("treatment")
 */
class TreatmentController extends Controller
{

    /**
     * Lists all treatment entities.
     *
     * @Route("/", name="treatment_index")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('AppBundle:Treatment')->createQueryBuilder('t');

        return $this->get('app.datagrid_utils')->handleDatagrid(
            $this->get('app.treatment_filter.form'),
            $request,
            $qb,
            function ($qb, $filterData) {
                FilterUtils::buildTextGreedyCondition(
                    $qb,
                    array(
                        'name',
                        //'price',
                        'code',
                    ),
                    $filterData['string']
                );
            },
            '@App/Treatment/include/grid.html.twig'
        );
    }

    /**
     * Creates a new treatment entity.
     *
     * @Route("/new", name="treatment_create")
     * @Method({"GET", "POST"})
     * @Template("@App/Treatment/update.html.twig")
     */
    public function createAction(Request $request)
    {
        $treatment = $this->get('app.entity_factory')->createTreatment();

        $result = $this->update($treatment);

        //$this->dumpDie($result);

        return $result;
    }

    /**
     * Displays a form to edit an existing treatment entity.
     *
     * @Route("/{id}/update", name="treatment_update")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Treatment $treatment)
    {
        return $this->update($treatment);
    }

    /**
     * Deletes a treatment entity.
     *
     * @Route("/{id}/delete", name="treatment_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Treatment $treatment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($treatment);
        $em->flush();

        $this->addFlash(
            'success',
            'app.treatment.message.deleted'
        );

        return $this->redirectToRoute('treatment_index');
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.treatment.form'),
            '@App/Treatment/include/form.html.twig',
            $entity,
            'app.treatment.message.created',
            'app.treatment.message.updated',
            'treatment_index',
            $this->get('app.hasher')->encodeObject($entity)
        );
    }

    /**
     * Returns treatment price.
     *
     * @Route("/price/{id}", name="treatment_price_view", options={"expose"=true})
     * @Method("POST")
     */
    public function viewPriceAction(Treatment $treatment)
    {
        $pricesData = array(
            'price' => trim(
                $treatment->getPrice()
            ),
        );

        foreach ($treatment->getConcessionPrices() as $concessionPrice) {
            $pricesData[$concessionPrice->getConcession()->getName()] = $concessionPrice->getPrice();
        }

        return new JsonResponse(
            json_encode(
                $pricesData
            )
        );
    }
}
