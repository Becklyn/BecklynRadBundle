<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
trait TimestampsTrait
{
    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(name="time_created", type="datetime_immutable")
     */
    private $timeCreated;


    /**
     * @var \DateTimeImmutable|null
     *
     * @ORM\Column(name="time_modified", type="datetime_immutable", nullable=true)
     */
    private $timeModified;



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
