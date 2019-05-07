<?php declare(strict_types=1);

namespace Becklyn\RadBundle;

use Becklyn\RadBundle\Bundle\BundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 *
 */
class BecklynRadBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function getContainerExtension ()
    {
        return new BundleExtension($this);
    }
}
