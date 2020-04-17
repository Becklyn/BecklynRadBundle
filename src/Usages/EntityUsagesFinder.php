<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Usages;

use Becklyn\RadBundle\Entity\Interfaces\EntityInterface;
use Becklyn\RadBundle\Translation\BackendTranslator;
use Becklyn\RadBundle\Usages\Data\ResolvedEntityUsage;
use Symfony\Component\Routing\RouterInterface;

/**
 * @deprecated Use becklyn/entity-admin instead.
 */
final class EntityUsagesFinder
{
    /**
     * @var iterable|EntityUsagesProviderInterface[]
     */
    private $finders;


    /**
     * @var RouterInterface
     */
    private $router;


    /**
     * @var BackendTranslator
     */
    private $backendTranslator;


    /**
     * @param iterable|EntityUsagesProviderInterface[] $finders
     */
    public function __construct (
        iterable $finders,
        RouterInterface $router,
        BackendTranslator $backendTranslator
    )
    {
        $this->finders = $finders;
        $this->router = $router;
        $this->backendTranslator = $backendTranslator;
    }


    /**
     * Finds all usages and returns them grouped by their group label.
     *
     * @return array<string, ResolvedEntityUsage[]>
     */
    public function findUsages (EntityInterface $entity) : array
    {
        @\trigger_error("The entity usages from becklyn/rad-bundle are deprecated. Use becklyn/entity-admin instead.", \E_USER_DEPRECATED);
        $grouped = [];
        $ungrouped = [];

        foreach ($this->finders as $finder)
        {
            foreach ($finder->getUsages($entity) as $usage)
            {
                $resolved = $usage->resolve($this->router);

                if (null !== $usage->getGroup())
                {
                    $group = $this->backendTranslator->t($usage->getGroup());
                    $grouped[$group][] = $resolved;
                }
                else
                {
                    $ungrouped[] = $resolved;
                }
            }
        }

        \uksort($grouped, "strnatcasecmp");

        if (!empty($ungrouped))
        {
            $ungroupedLabel = $this->backendTranslator->t("entity_usage.ungrouped");
            $grouped[$ungroupedLabel] = $ungrouped;
        }

        return $grouped;
    }
}
