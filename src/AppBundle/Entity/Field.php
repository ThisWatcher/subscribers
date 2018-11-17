<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    protected $type;

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

}