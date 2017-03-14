<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 11:14
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Treatment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $treatments = $em->getRepository('AppBundle:Treatment')->findAll();

        return array(
            'treatments' => $treatments,
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

        return $this->update($treatment);
    }

    /**
     * Finds and displays a treatment entity.
     *
     * @Route("/{id}", name="treatment_view")
     * @Method("GET")
     * @Template()
     */
    public function viewAction(Treatment $treatment)
    {
        return array(
            'entity' => $treatment,
        );
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
            $entity,
            'app.treatment.message.created',
            'app.treatment.message.updated',
            'treatment_view',
            $entity->getId()
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
        return new JsonResponse(
            json_encode(
                array(
                    'price' => trim(
                        $treatment->getPrice()
                    ),
                )
            )
        );
    }
}
