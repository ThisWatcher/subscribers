<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="`subscriber`", indexes={
 *      @ORM\Index(name="email_idx", columns={"email"}),
 * },
 * options={"collate"="utf8_lithuanian_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Subscriber extends AbstractEntity
{

    const STATUS_UNCONFIRMED = 'UNCONFIRMED';
    const STATUS_JUNK = 'JUNK';
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_UNSUBSCRIBED = 'UNSUBSCRIBED';
    const STATUS_BOUNCED = 'BOUNCED';

    public static $statusList = array(
        self::STATUS_UNCONFIRMED,
        self::STATUS_JUNK,
        self::STATUS_ACTIVE,
        self::STATUS_UNSUBSCRIBED,
        self::STATUS_BOUNCED,
    );

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255)
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
    protected $state;

    /**
     * @var Collection|Field[]
     * @ORM\ManyToMany(targetEntity="Field")
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
     * @return Collection|Field[]|ArrayCollection
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
        $this->fields[] = $field;

        return $this;
    }
}