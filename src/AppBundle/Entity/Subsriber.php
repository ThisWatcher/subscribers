<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`subscriber`", indexes={
 *      @ORM\Index(name="email_idx", columns={"email"}),
 * },
 * options={"collate"="utf8_lithuanian_ci"})
 */
class Subscriber extends AbstractEntity
{

    const STATUS_UNCONFIRMED = 'UNCONFIRMED';
    const STATUS_JUNK = 'JUNK';
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_UNSUBSCRIBED = 'UNSUBSCRIBED';
    const STATUS_BOUNCED = 'BOUNCED';

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="state", type="string", length=255)
     */
    protected $state;
}