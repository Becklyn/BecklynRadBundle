<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Usages;

use Becklyn\RadBundle\Entity\Interfaces\EntityInterface;
use Becklyn\RadBundle\Usages\Data\EntityUsage;

/**
 * @deprecated Use becklyn/entity-admin instead.
 */
interface EntityUsagesProviderInterface
{
    /**
     * Find the usages of the entity and returns references / links to it.
     *
     * @return EntityUsage[]
     */
    public function getUsages (EntityInterface $entity) : array;
}
