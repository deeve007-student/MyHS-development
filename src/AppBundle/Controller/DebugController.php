<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 16.03.2017
 * Time: 11:07
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\BulkPatientList;
use AppBundle\Entity\CommunicationsSettings;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\Message;
use AppBundle\Entity\Refund;
use AppBundle\Utils\AppNotificator;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Hashids\Hashids;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Mailgun\Mailgun;
use Mailgun\Model\Event\Event;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;
use Twilio\Rest\Client;
use Twilio\Rest\Lookups;
use UserBundle\Entity\User;

/**
 * Debug controller.
 *
 * @Route("debug")
 */
class DebugController extends Controller
{

    /**
     * @Route("/refund", name="debug_refund")
     * @Method("GET")
     */
    public function refundAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $invoiceId = $this->get('app.hasher')->decode('K7X5jBn5eP', Invoice::class);

        /** @var Invoice $invoice */
        $invoice = $em->getRepository('AppBundle:Invoice')->find($invoiceId);
        $products = $invoice->getInvoiceProducts();
        $product = $products[0];

        $paymentMethod = $em->getRepository('AppBundle:InvoicePaymentMethod')->findOneBy(array(
            'name' => 'Cash',
        ));

        $refund = new Refund();
        $refund->setInvoice($invoice);

        $productRefund = new InvoiceProductRefund();
        $productRefund->setItem($product);
        $productRefund->setAmount(1);

        $refund->addItem($productRefund);

        $em->persist($refund);
        $em->flush();

        die();
    }

    /**
     * @Route("/search-phone/{number}", name="debug_phone3")
     * @Method("GET")
     */
    public function searchPhoneAction(Request $request, $number)
    {
        $phoneUtils = $this->get('app.phone_utils');

        VarDumper::dump($phoneUtils->getPatientByPhoneNumber($number));

        die();
    }

    /**
     * @Route("/remove-lock", name="debug_remove_lock")
     * @Method("GET")
     */
    public function removeLockAction(Request $request)
    {
        $session = $request->getSession();
        $session->set('failedDateTime', null);
        $session->set('failedCounter', 0);

        die();
    }

    /**
     * @Route("/fixtures", name="debug_fixtures")
     * @Method("GET")
     */
    public function fixturesAction(Request $request)
    {
        $file = $this->getParameter('kernel.root_dir') . '/../temp/fixtures/products.xlsx';
        $inputFileType = \PHPExcel_IOFactory::identify($file);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($file);

        $toMoney = function ($val) {
            $val = (double)preg_replace('/[^\d,\.]/i', '', $val);
            return $val;
        };

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($worksheet->getRowIterator() as $row) {
            if ($row->getRowIndex() > 1) {
                $name = $worksheet->getCell('A' . $row->getRowIndex())->getValue();
                $cost = $toMoney($worksheet->getCell('D' . $row->getRowIndex())->getFormattedValue());

                echo $name . ' - ' . $cost . '<br/>';
            }
        }
        die();
    }

    /**
     * @Route("/valid-phone2", name="debug_valid_phone2")
     * @Method("GET")
     */
    public function validPhone2(Request $request)
    {
        $phone = '7 981 757 80 02';
        $phone = '+61 1300 551 119';
        //$phone = '1300 551 119';
        $region = 'AU';
        //$region = null;

        $phoneUtils = $this->get('app.phone_utils');

        if ($phoneUtils->isValidPhone($phone, $region)) {
            $number = $phoneUtils->getPhoneNumberFromString($phone, $region);
            VarDumper::dump($number);
        } else {
            VarDumper::dump('invalid');
        }

        if ($phoneUtils->isValidMobilePhone($phone, $region)) {
            VarDumper::dump('mobile');
        } else {
            VarDumper::dump('not mobile');
        }

        die();
    }

    /**
     * @Route("/def-reg", name="debug_def_reg")
     * @Method("GET")
     */
    public function drAction(Request $request)
    {
        $phoneUtils = $this->get('app.phone_utils');
        VarDumper::dump($phoneUtils->defaultRegion);

        die();
    }


    /**
     * @Route("/phone2", name="debug_phone2")
     * @Method("GET")
     */
    public function phoneTwoAction()
    {
        $validator = $this->get('validator');
        $formatter = $this->get('app.formatter');

        $arr = array(
            '+1 323 302-9281',
            '+13233438002',
            '+79817578002',
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

        $phoneUtils = $this->get('libphonenumber.phone_number_util');
        foreach ($arr as $num) {
            $number = $phoneUtils->parse($num, 'AU');

            $violations = $validator->validate($num, [new PhoneNumber(array('defaultRegion' => "AU"))]);

            if (count($violations) == 0) {
                VarDumper::dump($num . ' => ' . $formatter->formatPhoneCallable($number));
            } else {
                VarDumper::dump('Invalid: ' . $num);
            }

            //VarDumper::dump($number);

        }

        die();
    }


    /**
     * @Route("/sms", name="debug_sms")
     * @Method("GET")
     */
    public function smsAction()
    {
        $testSid = 'AC43acc35f1582b3e62a03298061c96335';
        $testToken = 'f7e07d71fb76c6073e7b9948c2a1dd79';

        $sid = 'ACdcd4f53c1e240ede82526068262c91aa';
        $token = 'fe42faa90ff1e7a3132597ee33f37b40';

        $alina = '+79811204351';
        $stepa = '+79817578002';
        $david = '+61436412348';
        $testFrom = '+15005550006';
        $testTo = '+14108675309';

        $testClient = new Client($testSid, $testToken);
        $client = $this->get('twilio');
        $twilioUtils = $this->get('app.twilio_utils');

        /*
        $number = $client->incomingPhoneNumbers->create(
            array("phoneNumber" => "+15005550006")
        );

        VarDumper::dump($number->sid);
        */

        $sms = $client->messages->create(
            $stepa,
            array(
                "from" => $david,
                "body" => "Test 3"
            )
        );

        VarDumper::dump($sms);

        /*
        $patients = $this->getDoctrine()->getManager()->getRepository('AppBundle:Patient')->findAll();
        $country = $patients[0]->getState()->getCountry();
        $pricing = $twilioUtils->getAverageSmsCost($country);

        VarDumper::dump($pricing);
        */

        die();
    }

    /**
     * @Route("/tz", name="debug_tz")
     * @Method("GET")
     */
    public function tzAction()
    {
        $dt = new \DateTime('2017-08-21 19:35');

        VarDumper::dump($dt);

        $dt->setTimezone(\DateTime::createFromFormat('O', '+03:00')->getTimezone());

        VarDumper::dump($dt);

        if ($event = $this->getDoctrine()->getManager()->getRepository('AppBundle:Event')->find(27)) {
            VarDumper::dump($event->getStart());
        }

        die();
    }

    /**
     * @Route("/au", name="debug_au")
     * @Method("GET")
     */
    public function auAction()
    {
        VarDumper::dump(\DateTimeZone::listIdentifiers(\DateTimeZone::AUSTRALIA));
        \DateTimeZone::

        die();
    }

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
     * @Route("/datetime", name="debug_datetime")
     * @Method("GET")
     */
    public function dateTimeAction()
    {
        $dt = new \DateTime();
        VarDumper::dump($dt);
        die($dt->format('j M Y g:i A'));
    }

    /**
     * @Route("/cal", name="debug_cal")
     * @Method("GET")
     */
    public function calTimeAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        /*
        $formatter = $this->get('app.formatter');
        $wdStart = \DateTime::createFromFormat($formatter->getBackendTimeFormat(), $user->getCalendarSettings()->getWorkDayStart());
        $wdEnd = \DateTime::createFromFormat($formatter->getBackendTimeFormat(), $user->getCalendarSettings()->getWorkDayEnd());

        $hours = array();
        for ($i = 0; $i < $wdEnd->diff($wdStart)->h; $i++) {
            $hours[] = (clone $wdStart)->modify('+ ' . $i . 'hours')->format($formatter->getBackendHoursFormat());
        }
        */

        $hours = array();
        for ($i = 0; $i <= 12; $i++) {
            $hours[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $minutes = array();
        for ($i = 0; $i < 60; $i = $i + (int)$user->getCalendarSettings()->getTimeInterval()) {
            $minutes[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        VarDumper::dump($hours);
        VarDumper::dump($minutes);

        die();
    }

    /**
     * @Route("/valid", name="debug_valid")
     * @Method("GET")
     */
    public function validAction()
    {
        $app = new Appointment();
        $dt = new \DateTime();
        $app->setStart($dt)
            ->setEnd($dt);

        $viols = $this->get('validator')->validate($app);
        $this->dump($viols);
        die();
    }

    /**
     * @Route("/events", name="debug_events")
     * @Method("GET")
     */
    public function eventsTimeAction()
    {
        foreach ($this->getDoctrine()->getManager()->getRepository('AppBundle:Event')->findAll() as $someEvent) {
            echo ClassUtils::getParentClass($someEvent) . '<br/>';
        }
        die();
    }

    /**
     * @Route("/templater", name="debug_templater")
     * @Method("GET")
     */
    public function templaterAction()
    {
        $templater = $this->get('app.templater');

        $invoice = $this->getDoctrine()->getManager()->getRepository('AppBundle:Invoice')->findAll()[0];
        VarDumper::dump($templater->compile($this->getUser()->getInvoiceSettings()->getInvoiceEmail(), array(
            'entity' => $invoice,
        )));

        /** @var CommunicationsSettings $communicationsSettings */
        $communicationsSettings = $this->getUser()->getCommunicationsSettings();
        $entity = $this->getDoctrine()->getManager()->getRepository('AppBundle:Appointment')->findAll()[0];

        VarDumper::dump($templater->compile($communicationsSettings->getAppointmentCreationEmail(), array(
            'entity' => $entity,
        )));
        VarDumper::dump($templater->compile($communicationsSettings->getAppointmentCreationSms(), array(
            'entity' => $entity,
        )));
        VarDumper::dump($templater->compile($communicationsSettings->getAppointmentReminderEmail(), array(
            'entity' => $entity,
        )));
        VarDumper::dump($templater->compile($communicationsSettings->getAppointmentReminderSms(), array(
            'entity' => $entity,
        )));

        $entity = $this->getDoctrine()->getManager()->getRepository('AppBundle:Recall')->findAll()[0];

        VarDumper::dump($templater->compile($communicationsSettings->getRecallEmail(), array(
            'entity' => $entity,
        )));

        VarDumper::dump($templater->compile($communicationsSettings->getRecallSms(), array(
            'entity' => $entity,
        )));

        die();
    }

    /**
     * @Route("/bulk", name="debug_bulk")
     * @Method("GET")
     */
    public function bulkAction()
    {
        $em = $this->getDoctrine()->getManager();

        /** @var QueryBuilder $qbManual */
        $qbManual = $em->getRepository('AppBundle:Message')->createQueryBuilder('l');
        $qbManual
            ->select('mc.id')
            ->leftJoin('l.patient', 'p')
            ->leftJoin('l.manualCommunication', 'mc')
            ->where('l.parentMessage IS NULL')
            ->andWhere('l.manualCommunication IS NOT NULL')
            ->orderBy('l.createdAt', 'DESC')
            ->groupBy('mc.id');

        VarDumper::dump($qbManual->getQuery()->getResult());

        die();
    }

    /**
     * @Route("/send", name="debug_send")
     * @Method("GET")
     */
    public function sendAction()
    {
        $message = new Message(Message::TYPE_EMAIL);
        $message->setRecipient('stepan.sib@gmail.com')
            ->setSubject('Проверка нотификатора')
            ->setBodyData('<strong>Проверочка!</strong>');

        $this->get('app.notificator')->sendMessage($message);

        die();
    }

    /**
     * @Route("/bounce", name="debug_bounce")
     * @Method("GET")
     */
    public function bounceAction()
    {
        $message = new Message();
        $message->setSid('<20180516063416.1.C1DA573E49206C2A@dev-space.pro>');

        VarDumper::dump($this->get('app.mailgun_utils')->getMessageStatus($message));
        die();
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


    /**
     * @Route("/t-files", name="debug_t_files")
     * @Method("GET")
     */
    public function tFilesAction()
    {
        $treatments = $this->getDoctrine()->getManager()->getRepository('AppBundle:Treatment')->findAll();

        foreach ($treatments as $treatment) {
            if ($treatment->getAttachment()) {
                VarDumper::dump($treatment);
            }
        }

        die();
    }

}
