<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 04.03.2017
 * Time: 11:01
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="manual_communication_attachment")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable()
 */
class ManualCommunicationAttachment
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
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="attachment", fileNameProperty="fileName")
     *
     * @var File
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $fileName;

    /**
     * @ORM\Column(type="integer", length=255)
     *
     * @var string
     */
    private $fileSize;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $originFileName;

    /**
     * @var ManualCommunication
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ManualCommunication", cascade={"persist"})
     * @ORM\JoinColumn(name="manual_communication_id", referencedColumnName="id", nullable=false)
     */
    protected $manualCommunication;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getFileName();
    }

    /**
     * @return bool
     */
    public function isImage()
    {
        return (explode('/', $this->getFile()->getMimeType())[0] == 'image') ? true : false;
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
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return ManualCommunicationAttachment
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;

        if ($file) {
            $fnameParts = explode('_', $file->getFilename());
            unset($fnameParts[0]);
            $this->setOriginFileName(implode('_', $fnameParts));
            $this->setFileSize($file->getSize());
            $this->setCreatedAt(new \DateTime());
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return ManualCommunicationAttachment
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set originFileName
     *
     * @param string $originFileName
     * @return ManualCommunicationAttachment
     */
    public function setOriginFileName($originFileName)
    {
        $this->originFileName = $originFileName;

        return $this;
    }

    /**
     * Get originFileName
     *
     * @return string
     */
    public function getOriginFileName()
    {
        return $this->originFileName;
    }

    /**
     * Set fileSize
     *
     * @param integer $fileSize
     * @return ManualCommunicationAttachment
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * Get fileSize
     *
     * @return integer
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @return ManualCommunication
     */
    public function getManualCommunication()
    {
        return $this->manualCommunication;
    }

    /**
     * @param ManualCommunication $manualCommunication
     * @return ManualCommunicationAttachment
     */
    public function setManualCommunication($manualCommunication)
    {
        $this->manualCommunication = $manualCommunication;
        return $this;
    }

}