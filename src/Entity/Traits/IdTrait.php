<?php declare(strict_types=1);

namespace Becklyn\Rad\Entity\Traits;

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
     */
    private ?int $id = null;



    /**
     */
    public function getId () : ?int
    {
        return $this->id;
    }


    /**
     * Returns whether this entity was already persisted and flushed (`false`) or if it is new (`true`).
     *
     * @return bool true if not yet flushed, false otherwise
     */
    public function isNew () : bool
    {
        return null === $this->id;
    }
}
