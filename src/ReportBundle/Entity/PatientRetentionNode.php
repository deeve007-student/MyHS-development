<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.05.2017
 * Time: 14:58
 */

namespace ReportBundle\Entity;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Recall;
use Doctrine\Common\Collections\ArrayCollection;

class PatientRetentionNode extends Node
{

    /** @var  integer */
    protected $new = 0;

    /** @var  integer */
    protected $patients = 0;

    /** @var  double */
    protected $att5 = 0;

    /** @var  double */
    protected $att10 = 0;

    /** @var  double */
    protected $att20 = 0;

    /** @var  double */
    protected $att50 = 0;

    /**
     * @return int
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * @param double $new
     * @return PatientRetentionNode
     */
    public function setNew($new)
    {
        $this->new = $new;
        return $this;
    }

    /**
     * @return double
     */
    public function getAtt5()
    {
        return $this->att5;
    }

    /**
     * @param double $att5
     * @return PatientRetentionNode
     */
    public function setAtt5($att5)
    {
        $this->att5 = $att5;
        return $this;
    }

    /**
     * @return double
     */
    public function getAtt10()
    {
        return $this->att10;
    }

    /**
     * @param double $att10
     * @return PatientRetentionNode
     */
    public function setAtt10($att10)
    {
        $this->att10 = $att10;
        return $this;
    }

    /**
     * @return double
     */
    public function getAtt20()
    {
        return $this->att20;
    }

    /**
     * @param double $att20
     * @return PatientRetentionNode
     */
    public function setAtt20($att20)
    {
        $this->att20 = $att20;
        return $this;
    }

    /**
     * @return double
     */
    public function getAtt50()
    {
        return $this->att50;
    }

    /**
     * @param double $att50
     * @return PatientRetentionNode
     */
    public function setAtt50($att50)
    {
        $this->att50 = $att50;
        return $this;
    }

    /**
     * @return double
     */
    public function getAtt5p()
    {
        return (int)(($this->att5 / $this->patients)*100);
    }

    /**
     * @return double
     */
    public function getAtt10p()
    {
        return (int)(($this->att10 / $this->patients)*100);
    }

    /**
     * @return double
     */
    public function getAtt20p()
    {
        return (int)(($this->att20 / $this->patients)*100);
    }

    /**
     * @return double
     */
    public function getAtt50p()
    {
        return (int)(($this->att50 / $this->patients)*100);
    }

    /**
     * @return int
     */
    public function getPatients()
    {
        return $this->patients;
    }

    /**
     * @param int $patients
     * @return PatientRetentionNode
     */
    public function setPatients($patients)
    {
        $this->patients = $patients;
        return $this;
    }


}
