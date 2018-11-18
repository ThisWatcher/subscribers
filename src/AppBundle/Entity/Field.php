<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="field",
 * options={"collate"="utf8_lithuanian_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Field extends AbstractEntity
{

    const TYPE_DATE = 'DATE';
    const TYPE_NUMBER = 'NUMBER';
    const TYPE_STRING = 'STRING';
    const TYPE_BOOLEAN = 'BOOLEAN';

    /**
     * @JMS\Exclude();
     */
    public static $typeList = array(
        self::TYPE_DATE,
        self::TYPE_NUMBER,
        self::TYPE_STRING,
        self::TYPE_BOOLEAN,
    );

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     * @JMS\Exclude();
     */
    protected $type;

    /**
     * @var string
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    protected $value;

    /**
     * @ORM\ManyToOne(targetEntity="Subscriber", inversedBy="fields", cascade={"remove"})
     * @ORM\JoinColumn(name="subscriber_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $subscriber;

    public function __toString()
    {
        return (string) $this->getType() . ' - ' . $this->getValue();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Field
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * @return Field
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Field
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubscriber()
    {
        return $this->subscriber;
    }

    /**
     * @param mixed $subscriber
     * @return Field
     */
    public function setSubscriber($subscriber)
    {
        $this->subscriber = $subscriber;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {

        if ($this->checkIfStringIsDate($this->getValue())) {
            $this->setType(self::TYPE_DATE);
        } elseif ($this->checkIfStringIsBool($this->getValue())) {
            $this->setType(self::TYPE_BOOLEAN);
        } elseif ($this->checkIfStringIsNumber($this->getValue())) {
            $this->setType(self::TYPE_NUMBER);
        } else {
            $this->setType(self::TYPE_STRING);
        }
    }

    function checkIfStringIsDate($string) {
        try {
            new \DateTime($string);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    function checkIfStringIsBool($string){
        $string = strtolower($string);
        return (in_array($string, ["true", "false", "1", "0", "yes", "no"], true));
    }

    function checkIfStringIsNumber($string){
        return is_numeric($string);
    }

}