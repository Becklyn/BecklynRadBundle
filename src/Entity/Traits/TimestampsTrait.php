<?php declare(strict_types=1);

namespace Becklyn\Rad\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
trait TimestampsTrait
{
    /**
     * @ORM\Column(name="time_created", type="datetime_immutable")
     */
    private \DateTimeImmutable $timeCreated;


    /**
     * @ORM\Column(name="time_modified", type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $timeModified = null;



    /**
     */
    public function getTimeCreated () : \DateTimeImmutable
    {
        return $this->timeCreated;
    }



    /**
     */
    public function getTimeModified () : ?\DateTimeImmutable
    {
        return $this->timeModified;
    }



    /**
     */
    public function markAsModified () : void
    {
        $this->timeModified = new \DateTimeImmutable();
    }



    /**
     * Returns the most recent modification time.
     */
    public function getLastModificationTime () : \DateTimeImmutable
    {
        return $this->getTimeModified() ?? $this->getTimeCreated();
    }
}
