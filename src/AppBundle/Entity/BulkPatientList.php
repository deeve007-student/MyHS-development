<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 10.05.2018
 * Time: 21:15
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="bulk_patient_list")
 * @ORM\HasLifecycleCallbacks()
 */
class BulkPatientList
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
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $filters;

    /**
     * @var Patient[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Patient")
     * @ORM\JoinTable(name="bulk_patients",
     *      joinColumns={@ORM\JoinColumn(name="list_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="patient_id", referencedColumnName="id", onDelete="CASCADE")}
     *     )
     */
    protected $patients;

    public function __construct()
    {
        $this->patients = new ArrayCollection();
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
     * @return Patient[]|ArrayCollection
     */
    public function getPatients()
    {
        return $this->patients;
    }

    /**
     * @param Patient $patient
     * @return BulkPatientList
     */
    public function addPatient(Patient $patient)
    {
        $this->patients->add($patient);
        return $this;
    }

    /**
     * @param Patient $patient
     * @return BulkPatientList
     */
    public function removePatient(Patient $patient)
    {
        $this->patients->remove($patient);
        return $this;
    }

    /**
     * @return string
     */
    public function getFilters()
    {
        return json_decode($this->filters, true);
    }

    /**
     * @param string $filters
     * @return BulkPatientList
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
        return $this;
    }

}