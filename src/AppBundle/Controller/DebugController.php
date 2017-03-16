<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 16.03.2017
 * Time: 11:07
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Debug controller.
 *
 * @Route("debug")
 */
class DebugController extends Controller
{

    /**
     * @Route("/mail", name="debug_mail")
     * @Method("GET")
     */
    public function indexAction()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('testing@dev-space.pro')
            ->setReplyTo('testing@dev-space.pro')
            ->setTo('stepan.sib@gmail.com', 'Stepan')
            ->setBody('Hi Stepan, look at this amazing email',
                'text/html');

        $this->get('mailer')->send($message);

        return new Response('ok...');
    }
}
