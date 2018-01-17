<?php

namespace Becklyn\RadBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;


/**
 *
 */
trait TimestampsTrait
{
    /**
     * @var \DateTime
     * @ORM\Column(name="time_created", type="datetime_immutable")
     */
    private $timeCreated;


    /**
     * @var \DateTime|null
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
    public function getTimeModified ()
    {
        return $this->timeModified;
    }



    /**
     * @param \DateTime $timeModified
     */
    public function setTimeModified (\DateTime $timeModified)
    {
        $this->timeModified = $timeModified;
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
