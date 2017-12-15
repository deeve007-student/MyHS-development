<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 18:59
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\UnavailableBlock;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Unavailable block controller.
 *
 * @Route("unavailable-block")
 */
class UnavailableBlockController extends Controller
{

    /**
     * Creates a new unavailable_block entity.
     *
     * @Route("/new/{date}/{resourceId}", defaults={"date"=null, "resourceId"=null}, name="unavailable_block_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template("@App/UnavailableBlock/update.html.twig")
     */
    public function createAction(Request $request, $date, $resourceId)
    {
        $unavailableBlock = $this->get('app.entity_factory')->createUnavailableBlock();

        if ($date) {
            $dt = $dt = $this->getEventUtils()->parseDateFromUTC($date);
            $unavailableBlock->setStart($dt);
            $unavailableBlock->setEnd($dt);
        }

        if ($resourceId !== null) {
            $unavailableBlock->setResource($this->getUser()->getCalendarSettings()->getResources()->toArray()[$resourceId]);
        }

        $additionalData['title'] = 'app.unavailable_block.new';
        $additionalData['submit'] = 'app.unavailable_block.new';
        $additionalData = $this->getEventAdditionalData($request, $additionalData);

        return $this->update($unavailableBlock, $additionalData);
    }

    /**
     * Finds and displays an unavailable block entity.
     *
     * @Route("/{id}", name="unavailable_block_view")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function viewAction(UnavailableBlock $unavailableBlock)
    {
        return array(
            'eventClass' => Event::class,
            'entity' => $unavailableBlock,
        );
    }

    /**
     * Displays a form to edit an existing unavailable_block entity.
     *
     * @Route("/{id}/update", name="unavailable_block_update", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, UnavailableBlock $unavailableBlock)
    {
        if ($date = $request->get('date')) {
            $this->get('app.event_utils')->setEventDates($unavailableBlock, $date);
        }

        $additionalData['title'] = 'app.unavailable_block.edit';
        $additionalData['submit'] = 'app.action.save';
        $additionalData = $this->getEventAdditionalData($request, $additionalData);

        return $this->update($unavailableBlock, $additionalData);
    }

    /**
     * Deletes an unavailable_block entity.
     *
     * @Route("/{id}/delete", name="unavailable_block_delete", options={"expose"=true})
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, UnavailableBlock $unavailableBlock)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($unavailableBlock);
        $em->flush();

        $this->addFlash(
            'success',
            'app.unavailable_block.message.deleted'
        );

        return $this->redirectToRoute('calendar_index');
    }

    protected function update($entity,$additionalData = array())
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.unavailable_block.form'),
            '@App/UnavailableBlock/include/form.html.twig',
            $entity,
            'app.unavailable_block.message.created',
            'app.unavailable_block.message.updated',
            'calendar_index',
            null,
            null,
            $additionalData
        );
    }
}
