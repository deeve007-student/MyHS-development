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
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTreatmentNoteFields()
    {
        return $this->treatmentNoteFields;
    }
}
