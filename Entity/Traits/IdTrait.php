<?php

namespace Becklyn\RadBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;


/**
 *
 */
trait IdTrait
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     *
     * @var int|null
     */
    private $id;



    /**
     * @return int|null
     */
    public function getId ()
    {
        return $this->id;
    }
}
