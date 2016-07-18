<?php

namespace Becklyn\RadBundle\Entity\Extension;

use Doctrine\ORM\Mapping as ORM;


/**
 *
 */
trait EntityTimestamps
{
    /**
     * @var \DateTime
     * @ORM\Column(name="time_created", type="datetime")
     */
    private $timeCreated;


    /**
     * @var \DateTime|null
     * @ORM\Column(name="time_modified", type="datetime", nullable=true)
     */
    private $timeModified;



    /**
     * @return \DateTime
     */
    public function getTimeCreated ()
    {
        return $this->timeCreated;
    }



    /**
     * @return \DateTime|null
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
}
