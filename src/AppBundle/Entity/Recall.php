<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 05.08.2017
 * Time: 21:26
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="recall")
 * @ORM\HasLifecycleCallbacks()
 */
class Recall
{
    use OwnerFieldTrait;
    use CreatedUpdatedTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=false)
     */
    protected $date;

    /**
     * @var Patient
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Patient", inversedBy="invoices")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id", nullable=false)
     */
    protected $patient;

    /**
     * @var RecallType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RecallType")
     * @ORM\JoinColumn(name="recall_type_id", referencedColumnName="id", nullable=false)
     */
    protected $recallType;

    /**
     * @var RecallFor
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RecallFor")
     * @ORM\JoinColumn(name="recall_for_id", referencedColumnName="id", nullable=true)
     */
    protected $recallFor;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $text;

    public function __toString()
    {
        return $this->getPatient().' - Recall';
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Recall
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set patient
     *
     * @param \AppBundle\Entity\Patient $patient
     * @return Recall
     */
    public function setPatient(\AppBundle\Entity\Patient $patient = null)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * Get patient
     *
     * @return \AppBundle\Entity\Patient
     */
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * @param RecallType $recallType
     * @return Recall
     */
    public function setRecallType(RecallType $recallType = null)
    {
        $this->recallType = $recallType;

        return $this;
    }

    /**
     * @return RecallType
     */
    public function getRecallType()
    {
        return $this->recallType;
    }

    /**
     * @param RecallFor $recallFor
     * @return Recall
     */
    public function setRecallFor(RecallFor $recallFor = null)
    {
        $this->recallFor = $recallFor;

        return $this;
    }

    /**
     * @return RecallFor
     */
    public function getRecallFor()
    {
        return $this->recallFor;
    }


    /**
     * Set text
     *
     * @param string $text
     * @return Recall
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }
}