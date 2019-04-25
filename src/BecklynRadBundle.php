<?php declare(strict_types=1);

namespace Becklyn\RadBundle;

use Becklyn\RadBundle\DependencyInjection\BecklynRadExtension;
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
        return new BecklynRadExtension();
    }

}
