<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 16.03.2017
 * Time: 14:16
 */

namespace AppBundle\Mailer;

class InsecureTransport extends \Swift_Transport_EsmtpTransport
{

    public function setInsecure()
    {
        $this->setStreamOptions(
            array(
                'ssl' => array(
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                ),
            )
        );
    }

}
