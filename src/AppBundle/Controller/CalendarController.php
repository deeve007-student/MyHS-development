<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.06.2017
 * Time: 17:25
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Calendar controller.
 *
 * @Route("calendar")
 */
class CalendarController extends Controller
{

    /**
     * @Route("/", name="calendar_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
