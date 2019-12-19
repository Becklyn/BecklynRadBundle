<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;


/**
 *
 */
trait SortOrderTrait
{
    /**
     * @var int|null
     * @ORM\Column(name="sort_order", type="integer")
     */
    private $sortOrder;



    /**
     * @return int|null
     */
    public function getSortOrder () : ?int
    {
        return $this->sortOrder;
    }



    /**
     * @param int $sortOrder
     */
    public function setSortOrder (int $sortOrder) : void
    {
        $this->sortOrder = $sortOrder;
    }
}
