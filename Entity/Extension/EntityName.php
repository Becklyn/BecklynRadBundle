<?php

namespace Becklyn\RadBundle\Entity\Extension;

use Doctrine\ORM\Mapping as ORM;


/**
 *
 */
trait EntityName
{
    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=1000)
     */
    private $name;



    /**
     * @return string
     */
    public function getName ()
    {
        return $this->name;
    }



    /**
     * @param string $name
     */
    public function setName ($name)
    {
        $this->name = $name;
    }
}
