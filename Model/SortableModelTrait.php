<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Model;

/**
 * Trait for doctrine models that use a sortable handler
 */
trait SortableModelTrait
{
    /**
     * Applies the sort order mapping as provided in the parameters.
     *
     * The model should wrap this method and use type hints on the $where parameter entries.
     *
     * @param array $sortMapping
     * @param array $where
     *
     * @return bool
     */
    protected function flushSortOrderMapping (array $sortMapping, array $where) : bool
    {
        if ($this->sortableHandler->applySorting($sortMapping, $where))
        {
            $this->flush();
            return true;
        }

        return false;
    }
}
