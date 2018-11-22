<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="`subscriber`", indexes={
 *      @ORM\Index(name="email_idx", columns={"email"}),
 * },
 * options={"collate"="utf8_lithuanian_ci"})
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Subscriber extends AbstractEntity
{

    const STATUS_UNCONFIRMED = 'UNCONFIRMED';
    const STATUS_JUNK = 'JUNK';
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_UNSUBSCRIBED = 'UNSUBSCRIBED';
    const STATUS_BOUNCED = 'BOUNCED';

    /**
     * @JMS\Exclude();
     */
    public static $statusList = array(
        self::STATUS_UNCONFIRMED,
        self::STATUS_JUNK,
        self::STATUS_ACTIVE,
        self::STATUS_UNSUBSCRIBED,
        self::STATUS_BOUNCED,
    );

    /**
     * @JMS\Expose();
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="state", type="string", length=255)
     */
    protected $state = self::STATUS_UNCONFIRMED;

    /**
     * @var \DateTime $deletedAt
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var Collection|fields[]
     * @ORM\OneToMany(targetEntity="Field", mappedBy="subscriber", cascade={"persist"})
     * @JMS\Exclude();
     */
    protected $fields;

    public function __construct()
    {
        $this->fields = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getEmail();
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Subscriber
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Subscriber
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return Subscriber
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return Collection|fields[]|ArrayCollection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addField(Field $field)
    {
        $field->setSubscriber($this);
        $this->getFields()->add($field);
        return $this;
    }

    public function removeField(Field $field)
    {
        $this->fields->removeElement($field);
        return $this;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("fields")
     */
    public function getSomeField()
    {
        $fields = $this->getFields();
        if (!$fields->isEmpty()) {
            foreach ($fields as $field) {
                $fieldsArray[$field->getTitle()] = $field->getValue();
            }
            return $fieldsArray;
        }
        return null;
    }
}