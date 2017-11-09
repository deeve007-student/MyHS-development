<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 30.05.2017
 * Time: 14:58
 */

namespace ReportBundle\Entity;

class AppointmentsNode extends Node
{

    protected $type;

    protected $reason;

    protected $originalStart;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return AppointmentsNode
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param mixed $reason
     * @return AppointmentsNode
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOriginalStart()
    {
        return $this->originalStart;
    }

    /**
     * @param mixed $originalStart
     * @return AppointmentsNode
     */
    public function setOriginalStart($originalStart)
    {
        $this->originalStart = $originalStart;
        return $this;
    }



}
