<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 16.03.2017
 * Time: 11:07
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Controller\Controller;
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
            ->setFrom('myhs@dev-space.pro')
            ->setReplyTo('myhs@dev-space.pro')
            ->setTo('stepan.sib@gmail.com', 'Stepan')
            ->setBody('Dear friend, this is a test email from MyHS', 'text/html');

        $this->get('mailer')->send($message);

        return new Response('Test message sent');
    }

    /**
     * @Route("/invoice-pdf", name="debug_invoice_pdf")
     * @Method("GET")
     */
    public function invoicePdfAction()
    {
        $this->get('knp_snappy.pdf')->generate('http://myhs.dev-space.pro', $this->getParameter('kernel.root_dir').'/../myhs.pdf');

        return new Response('Everything is ok');
    }

}
