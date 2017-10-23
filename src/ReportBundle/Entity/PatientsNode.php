<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.05.2017
 * Time: 14:58
 */

namespace ReportBundle\Entity;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Recall;

class PatientsNode extends Node
{

    /** @var  Appointment */
    protected $nextAppointment;

    /** @var  Recall[] */
    protected $recalls;

    public function __construct($object = null)
    {
        parent::__construct($object);
        $this->recalls = array();
    }

    /**
     * @return Appointment
     */
    public function getNextAppointment()
    {
        return $this->nextAppointment;
    }

    /**
     * @param Appointment $nextAppointment
     */
    public function setNextAppointment($nextAppointment)
    {
        $this->nextAppointment = $nextAppointment;
    }

    /**
     * @return Recall[]
     */
    public function getRecalls()
    {
        return $this->recalls;
    }

    /**
     * @param Recall $recall
     */
    public function addRecall($recall)
    {
        $this->recalls[] = $recall;
    }



}
