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
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var string $name
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $firstname;

    /**
     * @var string $name
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $lastname;

    /**
     * @var integer $age
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    public $age;

    /**
     * @var integer $friend
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $friend;
}
