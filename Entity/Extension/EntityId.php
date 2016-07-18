<?php

namespace Becklyn\RadBundle\Entity\Extension;

use Doctrine\ORM\Mapping as ORM;


/**
 *
 */
trait EntityId
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;



    /**
     * @return int
     */
    public function getId ()
    {
        return $this->id;
    }
}
