<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.11.17
 * Time: 15:13
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="invoice_settings")
 * @ORM\HasLifecycleCallbacks()
 */
class InvoiceSettings
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
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $invoiceNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $invoiceTitle;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $invoiceNotes;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $invoiceEmail;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $dueWithin;

    public function __toString()
    {
        return (string)$this->getId();
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
     * @return integer
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * @param integer $invoiceNumber
     * @return InvoiceSettings
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getDueWithin()
    {
        return $this->dueWithin;
    }

    /**
     * @param int $dueWithin
     * @return InvoiceSettings
     */
    public function setDueWithin($dueWithin)
    {
        $this->dueWithin = $dueWithin;
        return $this;
    }

    /**
     * @return string
     */
    public function getInvoiceTitle()
    {
        return $this->invoiceTitle;
    }

    /**
     * @param string $invoiceTitle
     * @return InvoiceSettings
     */
    public function setInvoiceTitle($invoiceTitle)
    {
        $this->invoiceTitle = $invoiceTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getInvoiceNotes()
    {
        return $this->invoiceNotes;
    }

    /**
     * @param string $invoiceNotes
     * @return InvoiceSettings
     */
    public function setInvoiceNotes($invoiceNotes)
    {
        $this->invoiceNotes = $invoiceNotes;
        return $this;
    }

    /**
     * @return string
     */
    public function getInvoiceEmail()
    {
        return $this->invoiceEmail;
    }

    /**
     * @param string $invoiceEmail
     * @return InvoiceSettings
     */
    public function setInvoiceEmail($invoiceEmail)
    {
        $this->invoiceEmail = $invoiceEmail;
        return $this;
    }

}
