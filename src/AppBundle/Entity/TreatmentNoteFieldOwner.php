<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 20.04.2017
 * Time: 16:39
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="treatment_note_field_owner")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\HasLifecycleCallbacks()
 */
class TreatmentNoteFieldOwner
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
     * @var TreatmentNoteField[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TreatmentNoteField", mappedBy="treatmentNoteFieldOwner", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $treatmentNoteFields;

    /**
     * @var TreatmentNoteFieldOwner
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TreatmentNoteFieldOwner", inversedBy="notes")
     * @ORM\JoinColumn(name="template_id", referencedColumnName="id", nullable=true)
     */
    protected $template;

    /**
     * @var TreatmentNoteFieldOwner[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TreatmentNoteFieldOwner", mappedBy="template", cascade={"remove"}, orphanRemoval=true)
     */
    protected $notes;

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
     * Constructor
     */
    public function __construct()
    {
        $this->treatmentNoteFields = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add treatmentNoteFields
     *
     * @param \AppBundle\Entity\TreatmentNoteField $treatmentNoteField
     * @return TreatmentNoteFieldOwner
     */
    public function addTreatmentNoteField(\AppBundle\Entity\TreatmentNoteField $treatmentNoteField)
    {
        $this->treatmentNoteFields[] = $treatmentNoteField;
        $treatmentNoteField->setTreatmentNoteFieldOwner($this);

        return $this;
    }

    /**
     * Remove treatmentNoteFields
     *
     * @param \AppBundle\Entity\TreatmentNoteField $treatmentNoteField
     */
    public function removeTreatmentNoteField(\AppBundle\Entity\TreatmentNoteField $treatmentNoteField)
    {
        $this->treatmentNoteFields->removeElement($treatmentNoteField);
        $treatmentNoteField->setTreatmentNoteFieldOwner(null);
    }

    /**
     * Get treatmentNoteFields
     *
     * @return \Doctrine\Common\Collections\Collection|TreatmentNoteField[]
     */
    public function getTreatmentNoteFields()
    {
        return $this->treatmentNoteFields;
    }

    public function getFieldValueByName($fieldName)
    {
        foreach ($this->getTreatmentNoteFields() as $field) {
            if ($field->getName() == $fieldName) {
                return $field->getValue();
            }
        }

        return null;
    }

    /**
     * Set template
     *
     * @param \AppBundle\Entity\TreatmentNoteFieldOwner $template
     * @return TreatmentNoteFieldOwner
     */
    public function setTemplate(\AppBundle\Entity\TreatmentNoteFieldOwner $template = null)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return \AppBundle\Entity\TreatmentNoteFieldOwner
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Add notes
     *
     * @param \AppBundle\Entity\TreatmentNoteFieldOwner $notes
     * @return TreatmentNoteFieldOwner
     */
    public function addNote(\AppBundle\Entity\TreatmentNoteFieldOwner $notes)
    {
        $this->notes[] = $notes;

        return $this;
    }

    /**
     * Remove notes
     *
     * @param \AppBundle\Entity\TreatmentNoteFieldOwner $notes
     */
    public function removeNote(\AppBundle\Entity\TreatmentNoteFieldOwner $notes)
    {
        $this->notes->removeElement($notes);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }
}
