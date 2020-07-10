<?php declare(strict_types=1);

namespace Becklyn\Rad\Entity\Tag;

use Becklyn\Rad\Entity\Interfaces\EntityInterface;
use Becklyn\Rad\Entity\Traits\IdTrait;
use Becklyn\Rad\Tags\TagInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Base class for implementing tag entities
 *
 * @ORM\MappedSuperclass()
 *
 * @UniqueEntity(fields={"tag"}, message="becklyn.rad.tag.duplicate")
 */
abstract class TagEntity implements EntityInterface, TagInterface
{
    use IdTrait;

    /**
     * @ORM\Column(name="tag", type="string", length=254, unique=true)
     *
     * @Assert\NotNull()
     * @Assert\Length(max="254")
     * @Assert\Regex(pattern="~^[a-z0-9\-_., ]+$~i", message="becklyn.rad.tag.pattern")
     */
    private ?string $tag = null;


    /**
     */
    public function getTag () : ?string
    {
        return $this->tag;
    }


    /**
     */
    public function setTag (?string $tag) : void
    {
        $this->tag = $tag;
    }


    /**
     * @inheritDoc
     */
    public function getTagLabel () : string
    {
        return (string) $this->tag;
    }
}
