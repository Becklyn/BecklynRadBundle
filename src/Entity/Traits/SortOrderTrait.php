<?php declare(strict_types=1);

namespace Becklyn\Rad\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
trait SortOrderTrait
{
    /**
     * @ORM\Column(name="sort_order", type="integer")
     */
    private ?int $sortOrder = null;



    /**
     * @return int
     */
    public function getSortOrder () : ?int
    {
        return $this->sortOrder;
    }



    /**
     */
    public function setSortOrder (int $sortOrder) : void
    {
        $this->sortOrder = $sortOrder;
    }
}
