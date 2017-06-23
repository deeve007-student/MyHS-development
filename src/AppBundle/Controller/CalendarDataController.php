<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.03.2017
 * Time: 18:38
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * CalendarData controller.
 */
class CalendarDataController extends Controller
{
    /**
     * Displays a form to edit an existing concession entity.
     *
     * @Route("/settings/calendar", name="calendar_data_update")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request)
    {
        return $this->update($this->getUser()->getCalendarData());
    }

    protected function update($entity)
    {
        return $this->get('app.entity_action_handler')->handleCreateOrUpdate(
            $this->get('app.calendar_data.form'),
            null,
            $entity,
            '',
            'app.calendar_data.message.updated',
            'calendar_data_update'
        );
    }
}
