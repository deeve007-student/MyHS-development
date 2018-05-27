<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 14.03.2017
 * Time: 18:49
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="treatment")
 * @Vich\Uploadable()
 */
class Treatment extends ConcessionPriceOwner
{

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $calendarColour;

    /**
     * @var InvoiceTreatment[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\InvoiceTreatment", mappedBy="treatment", cascade={"remove"}, orphanRemoval=true)
     */
    protected $invoiceTreatments;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $parent = false;

    /**
     * @var Treatment[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Treatment", mappedBy="parentTreatment")
     */
    protected $treatments;

    /**
     * @var Treatment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Treatment", inversedBy="treatments")
     * @ORM\JoinColumn(name="parent_treatment_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $parentTreatment;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $duration;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="attachment", fileNameProperty="attachmentFileName")
     *
     * @var File
     */
    private $attachment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $attachmentFileName;

    public function __toString()
    {
        return $this->getName();
    }

    public function getFullName()
    {
        $fullName = $this->getName();
        if ($this->getCode()) {
            $fullName .= ' (' . $this->getCode() . ')';
        }
        return $fullName;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Treatment
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
     * Set code
     *
     * @param string $code
     * @return Treatment
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Treatment
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set calendarColour
     *
     * @param string $calendarColour
     * @return Treatment
     */
    public function setCalendarColour($calendarColour)
    {
        $this->calendarColour = $calendarColour;

        return $this;
    }

    /**
     * Get calendarColour
     *
     * @return string
     */
    public function getCalendarColour()
    {
        return $this->calendarColour;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->concessionPrices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invoiceTreatments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->treatments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add concessionPrices
     *
     * @param \AppBundle\Entity\ConcessionPrice $concessionPrices
     * @return Treatment
     */
    public function addConcessionPrice(\AppBundle\Entity\ConcessionPrice $concessionPrices)
    {
        $this->concessionPrices[] = $concessionPrices;

        return $this;
    }

    /**
     * Remove concessionPrices
     *
     * @param \AppBundle\Entity\ConcessionPrice $concessionPrices
     */
    public function removeConcessionPrice(\AppBundle\Entity\ConcessionPrice $concessionPrices)
    {
        $this->concessionPrices->removeElement($concessionPrices);
    }

    /**
     * Get concessionPrices
     *
     * @return ConcessionPrice[]|\Doctrine\Common\Collections\Collection
     */
    public function getConcessionPrices()
    {
        return $this->concessionPrices;
    }

    /**
     * Add invoiceTreatments
     *
     * @param \AppBundle\Entity\InvoiceTreatment $invoiceTreatments
     * @return Treatment
     */
    public function addInvoiceTreatment(\AppBundle\Entity\InvoiceTreatment $invoiceTreatments)
    {
        $this->invoiceTreatments[] = $invoiceTreatments;

        return $this;
    }

    /**
     * Remove invoiceTreatments
     *
     * @param \AppBundle\Entity\InvoiceTreatment $invoiceTreatments
     */
    public function removeInvoiceTreatment(\AppBundle\Entity\InvoiceTreatment $invoiceTreatments)
    {
        $this->invoiceTreatments->removeElement($invoiceTreatments);
    }

    /**
     * Get invoiceTreatments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoiceTreatments()
    {
        return $this->invoiceTreatments;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param $duration
     * @return $this
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return Treatment[]|ArrayCollection
     */
    public function getTreatments()
    {
        return $this->treatments;
    }

    /**
     * @param Treatment[]|ArrayCollection $treatments
     * @return Treatment
     */
    public function setTreatments($treatments)
    {
        $this->treatments = $treatments;
        return $this;
    }

    /**
     * @return Treatment
     */
    public function getParentTreatment()
    {
        return $this->parentTreatment;
    }

    /**
     * @param Treatment $parentTreatment
     * @return Treatment
     */
    public function setParentTreatment($parentTreatment)
    {
        $this->parentTreatment = $parentTreatment;
        return $this;
    }

    /**
     * @return bool
     */
    public function isParent()
    {
        return $this->parent;
    }

    /**
     * @param bool $parent
     * @return Treatment
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return File
     */
    public function getAttachment()
    {
        return $this->attachment;
    }


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $attachment
     *
     * @return Treatment
     */
    public function setAttachment(File $attachment = null)
    {
        $this->attachment = $attachment;
        return $this;
    }

    /**
     * @return string
     */
    public function getAttachmentFileName()
    {
        return $this->attachmentFileName;
    }

    /**
     * @param string $attachmentFileName
     * @return Treatment
     */
    public function setAttachmentFileName($attachmentFileName)
    {
        $this->attachmentFileName = $attachmentFileName;
        return $this;
    }

}
