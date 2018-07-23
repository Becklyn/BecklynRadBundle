<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;


/**
 *
 */
trait TimestampsTrait
{
    /**
     * @var \DateTimeInterface
     * @ORM\Column(name="time_created", type="datetime_immutable")
     */
    private $timeCreated;


    /**
     * @var \DateTimeInterface|null
     * @ORM\Column(name="time_modified", type="datetime_immutable", nullable=true)
     */
    private $timeModified;



    /**
     * @return \DateTimeInterface
     */
    public function getTimeCreated () : \DateTimeInterface
    {
        return $this->timeCreated;
    }



    /**
     * @return \DateTimeInterface|null
     */
    public function getTimeModified () : ?\DateTimeInterface
    {
        return $this->timeModified;
    }



    /**
     * @param \DateTimeImmutable $timeModified
     */
    public function markAsModified () : void
    {
        $this->timeModified = new \DateTimeImmutable();
    }



    /**
     * Returns the most recent modification time
     *
     * @return \DateTimeInterface
     */
    public function getLastModificationTime () : \DateTimeInterface
    {
        return $this->getTimeModified() ?? $this->getTimeCreated();
    }
}
