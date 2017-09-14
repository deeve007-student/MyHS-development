<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 12.09.2017
 * Time: 13:35
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedUpdatedTrait;
use AppBundle\Entity\Traits\OwnerFieldTrait;
use AppBundle\Utils\Formatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\VarDumper\VarDumper;
use UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="message")
 * @ORM\HasLifecycleCallbacks()
 */
class Message
{

    use OwnerFieldTrait;
    use CreatedUpdatedTrait;

    const TYPE_SMS = 'sms';
    const TYPE_SMS_ICON = '<i class="fa fa-mobile" aria-hidden="true"></i>';

    const TYPE_EMAIL = 'email';
    const TYPE_EMAIL_ICON = '<i class="fa fa-envelope-o" aria-hidden="true"></i>';

    const TYPE_CALL = 'call';
    const TYPE_CALL_ICON = '<i class="fa fa-phone" aria-hidden="true"></i>';

    const TAG_RECALL = 'recall';
    const TAG_INVOICE_SENT = 'invoice_sent';
    const TAG_APPOINTMENT_CREATED = 'appointment_created';
    const TAG_APPOINTMENT_REMINDER = 'appointment_remind';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @var  string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $type;

    /** @var  string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $sid;

    /** @var  string */
    protected $typeOverride;

    /** @var  mixed */
    protected $recipient;

    /**
     * @var Patient
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Patient")
     * @ORM\JoinColumn(name="recipient_patient_id", referencedColumnName="id", nullable=true)
     */
    protected $patient;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="recipient_user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * @var double
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $price;

    /** @var  string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $recipientAddress;

    /**
     * @var string|array
     */
    protected $bodyData;

    /** @var  string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $body;

    /** @var  string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $error;

    /** @var  string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $subject;

    /** @var  array */
    protected $attachments = array();

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $routeData;

    /**
     * @var Message
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Message", inversedBy="replies")
     * @ORM\JoinColumn(name="parent_message_id", referencedColumnName="id", nullable=true)
     */
    protected $parentMessage;

    /**
     * @var Message[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Message", mappedBy="parentMessage")
     * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    protected $replies;

    /** @var  string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $tag;

    public function __construct($type = null)
    {
        $this->setTypeOverride($type);
        $this->replies = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTag();
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
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     * @return Message
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Message
     */
    protected function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getTypeOverride()
    {
        return $this->typeOverride;
    }

    /**
     * @param string $type
     * @return Message
     */
    public function setTypeOverride($type)
    {
        $this->typeOverride = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return Message
     */
    protected function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param mixed $recipient
     * @return $this
     * @throws \Exception
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecipientAddress()
    {
        return $this->recipientAddress;
    }

    /**
     * @param string $recipientAddress
     * @return Message
     */
    protected function setRecipientAddress($recipientAddress)
    {
        $this->recipientAddress = $recipientAddress;
        return $this;
    }

    /**
     * @return array|string
     */
    public function getBodyData()
    {
        return $this->bodyData;
    }

    /**
     * @param array|string $bodyData
     * @return Message
     */
    public function setBodyData($bodyData)
    {
        $this->bodyData = $bodyData;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return Message
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    public function addAttachment($attachment)
    {
        $this->attachments[] = $attachment;
        $this->setType(Message::TYPE_EMAIL);
        return $this;
    }

    /**
     * @param array $routeData
     * @return Message
     */
    public function setRouteData(array $routeData)
    {
        $this->routeData = base64_encode(json_encode($routeData));

        return $this;
    }

    /**
     * @return array
     */
    public function getRouteData()
    {
        return json_decode(base64_decode($this->routeData), true);
    }

    /**
     * Set patient
     *
     * @param \AppBundle\Entity\Patient $patient
     * @return Message
     */
    protected function setPatient(\AppBundle\Entity\Patient $patient)
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
     * Set user
     *
     * @param User $user
     * @return Message
     */
    protected function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Message
     */
    public function getParentMessage()
    {
        return $this->parentMessage;
    }

    /**
     * @param Message $parentMessage
     * @return Message
     */
    public function setParentMessage($parentMessage)
    {
        $this->parentMessage = $parentMessage;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Message
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getSid()
    {
        return $this->sid;
    }

    /**
     * @param string $sid
     * @return Message
     */
    public function setSid($sid)
    {
        $this->sid = $sid;
        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     * @return Message
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return Message[]
     */
    public function getReplies()
    {
        return $this->replies;
    }

    /**
     * @param Message $reply
     * @return Message
     */
    public function addReply($reply)
    {
        $this->replies->add($reply);
        return $this;
    }

    /**
     * @param Message $reply
     * @return Message
     */
    public function removeReply($reply)
    {
        $this->replies->removeElement($reply);
        return $this;
    }

    public function compile(\Twig_Environment $twig = null, Formatter $formatter)
    {
        $recipient = $this->getRecipient();

        $sendMethod = self::TYPE_EMAIL;
        if ($this->getRecipient() instanceof Patient) {
            $sendMethod = $recipient->getPreferredNotificationMethod();
        }
        if (count($this->getAttachments())) {
            $sendMethod = self::TYPE_EMAIL;
        }
        if ($this->getTypeOverride()) {
            $sendMethod = $this->getTypeOverride();
        }
        $this->setType($sendMethod);


        if (is_object($recipient)) {
            if ($recipient instanceof Patient) {
                $this->setPatient($recipient);
            }
            if ($recipient instanceof User) {
                $this->setUser($recipient);
            }
        }

        $recipientAddress = $recipient;

        switch ($sendMethod) {
            case self::TYPE_EMAIL:

                if ($recipient instanceof Patient) {
                    $recipientAddress = $recipient->getEmail();
                } elseif ($recipient instanceof User) {
                    $recipientAddress = $recipient->getEmail();
                }

                break;
            case self::TYPE_SMS:

                if ($recipient instanceof Patient) {
                    $recipientAddress = $formatter->formatPhone($recipient);
                } elseif ($recipient instanceof User) {
                    throw new \Exception('Cannot notificate practicioner with SMS');
                }

                break;
            case self::TYPE_CALL:

                if ($recipient instanceof Patient) {
                    $recipientAddress = $recipient->getMobilePhone();
                } elseif ($recipient instanceof User) {
                    throw new \Exception('Cannot notificate practicioner with call');
                }

                break;
            default:
                throw new \Exception('Undefined notification method: ' . $sendMethod);
                break;
        }
        $this->setRecipientAddress($recipientAddress);

        $bodyCompiled = '';
        if (is_array($this->getBodyData()) && $twig) {
            $bodyCompiled = $twig->render($this->getBodyData()['template'], $this->getBodyData()['data']);
        } elseif (is_string($this->getBodyData())) {
            $bodyCompiled = $this->getBodyData();
        }

        if ($this->getType() == self::TYPE_SMS) {
            $bodyCompiled = strip_tags($bodyCompiled);
        }
        $this->setBody($bodyCompiled);
    }

}
