<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class Person
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $firstname;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $lastname;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    public $age;

    /**
     * @var int
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $friend;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $birthday;
}
