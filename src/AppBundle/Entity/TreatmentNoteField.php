<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 20.04.2017
 * Time: 16:36
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="treatment_note_field")
 * @ORM\HasLifecycleCallbacks()
 */
class TreatmentNoteField
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
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $value;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $position;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $mandatory;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $notes;

    /**
     * @var TreatmentNoteFieldOwner
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TreatmentNoteFieldOwner", inversedBy="treatmentNoteFields")
     * @ORM\JoinColumn(name="treatment_note_field_owner_id", referencedColumnName="id", nullable=false)
     */
    protected $treatmentNoteFieldOwner;

    /**
     * @var TreatmentNoteField
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TreatmentNoteField", inversedBy="treatmentNoteFields")
     * @ORM\JoinColumn(name="template_field_id", referencedColumnName="id", nullable=true)
     */
    protected $templateField;

    /**
     * @var TreatmentNoteField[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TreatmentNoteField", mappedBy="templateField", cascade={"remove"}, orphanRemoval=true)
     */
    protected $noteFields;

    public function __toString()
    {
        return $this->getName();
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
     * Set name
     *
     * @param string $name
     * @return TreatmentNoteField
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set treatmentNoteFieldOwner
     *
     * @param \AppBundle\Entity\TreatmentNoteFieldOwner $treatmentNoteFieldOwner
     * @return TreatmentNoteField
     */
    public function setTreatmentNoteFieldOwner(\AppBundle\Entity\TreatmentNoteFieldOwner $treatmentNoteFieldOwner = null
    )
    {
        $this->treatmentNoteFieldOwner = $treatmentNoteFieldOwner;

        return $this;
    }

    /**
     * Get treatmentNoteFieldOwner
     *
     * @return \AppBundle\Entity\TreatmentNoteFieldOwner
     */
    public function getTreatmentNoteFieldOwner()
    {
        return $this->treatmentNoteFieldOwner;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return TreatmentNoteField
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set mandatory
     *
     * @param boolean $mandatory
     * @return TreatmentNoteField
     */
    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;

        return $this;
    }

    /**
     * Get mandatory
     *
     * @return boolean
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return TreatmentNoteField
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return TreatmentNoteField
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->noteFields = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set templateField
     *
     * @param \AppBundle\Entity\TreatmentNoteField $templateField
     * @return TreatmentNoteField
     */
    public function setTemplateField(\AppBundle\Entity\TreatmentNoteField $templateField = null)
    {
        $this->templateField = $templateField;

        return $this;
    }

    /**
     * Get templateField
     *
     * @return \AppBundle\Entity\TreatmentNoteField 
     */
    public function getTemplateField()
    {
        return $this->templateField;
    }

    /**
     * Add noteFields
     *
     * @param \AppBundle\Entity\TreatmentNoteField $noteFields
     * @return TreatmentNoteField
     */
    public function addNoteField(\AppBundle\Entity\TreatmentNoteField $noteFields)
    {
        $this->noteFields[] = $noteFields;

        return $this;
    }

    /**
     * Remove noteFields
     *
     * @param \AppBundle\Entity\TreatmentNoteField $noteFields
     */
    public function removeNoteField(\AppBundle\Entity\TreatmentNoteField $noteFields)
    {
        $this->noteFields->removeElement($noteFields);
    }

    /**
     * Get noteFields
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNoteFields()
    {
        return $this->noteFields;
    }
}
