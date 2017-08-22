<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 22.08.2017
 * Time: 17:10
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Attachment;
use AppBundle\Entity\Concession;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\InvoicePayment;
use AppBundle\Entity\Patient;
use AppBundle\Entity\PatientAlert;
use AppBundle\Entity\Product;
use AppBundle\Entity\Recall;
use AppBundle\Entity\RecurringTask;
use AppBundle\Entity\Task;
use AppBundle\Entity\Treatment;
use AppBundle\Entity\TreatmentNote;
use AppBundle\Entity\TreatmentNoteField;
use AppBundle\Entity\TreatmentNoteTemplate;
use AppBundle\Entity\UnavailableBlock;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use UserBundle\Entity\User;

class DateTimeUtils
{

    public function getTimezoneFromOffset($offset)
    {
        list($hours, $minutes) = explode(':', $offset);
        $seconds = $hours * 60 * 60 + $minutes * 60;
        $tz = timezone_name_from_abbr('', $seconds, 1);
        if ($tz === false) {
            $tz = timezone_name_from_abbr('', $seconds, 0);
        }
        return $tz;
    }

}
