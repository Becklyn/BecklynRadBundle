<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Tags;

interface TagInterface
{
    /**
     * Returns the tag label.
     */
    public function getLabel () : string;
}
