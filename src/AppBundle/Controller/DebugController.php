<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 16.03.2017
 * Time: 11:07
 */

namespace AppBundle\Controller;

use Hashids\Hashids;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber;
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
     * @Route("/phone", name="debug_validate")
     * @Method("GET")
     */
    public function phoneAction()
    {
        $validator = $this->get('validator');
        $dumper = new VarDumper();

        $arr = array(
            '+61412372849',

            '04 12345678',
            '04 23456789',
            '0434567890',
            '0445678901',
            '0456789012',

            '0445576356',
            '0445 576356',
            '0412372849',
            '02 46578369',
            '02 4657   836 9',
            '07243567395',
            '+79137533987',
            '1300',
            '1800',
            '021800',
            '041800',
            '071800',
            '1800ddd',
        );

        $arr = array(
            '0412 281 821',
            '(02) 4702 1390',
            '1300 551 119',
            '0430 031 700',
            '(03) 9675 0900',
            '(07) 5570 3425',
            '0428 151 230',
            '0430 066 070',
            '0430 066 070',
        );

        foreach ($arr as $phone) {
            echo $phone;
            $violations = $validator->validate($phone, [new PhoneNumber(array('defaultRegion' => "AU"))]);
            //$violations = $validator->validate($phone, [new PhoneNumber(array('defaultRegion'=>"AU"))]);
            if (count($violations) == 0) {
                echo ' - valid';
            } else {
                echo ' - invalid';
            }
            echo '<br/>';
        }

        die();
    }

}
