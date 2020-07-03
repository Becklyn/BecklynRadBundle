<?php declare(strict_types=1);

namespace Becklyn\Rad\Tags;

interface TagInterface
{
    /**
     * Returns the tag label.
     */
    public function getTagLabel () : string;
}
