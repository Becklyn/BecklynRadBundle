<?php

namespace Becklyn\RadBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;


/**
 *
 */
trait IdTrait
{
    /**
     *
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(name: 'id', type: 'integer')]
    private $id;



    /**
     * @return int
     */
    public function getId ()
    {
        return $this->id;
    }
}
