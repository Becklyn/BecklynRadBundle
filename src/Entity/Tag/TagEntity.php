<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Entity\Tag;

use Becklyn\RadBundle\Entity\Interfaces\EntityInterface;
use Becklyn\RadBundle\Entity\Traits\IdTrait;
use Becklyn\RadBundle\Tags\TagInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Base class for implementing tag entities
 *
 * @ORM\MappedSuperclass()
 *
 * @UniqueEntity(fields={"tag"}, message="becklyn_rad.tag.duplicate")
 */
abstract class TagEntity implements EntityInterface, TagInterface
{
    use IdTrait;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tag", type="string", length=254, unique=true)
     *
     * @Assert\NotNull()
     * @Assert\Length(max="254")
     * @Assert\Regex(pattern="~^[a-z0-9\\-_., ]+$~i", message="becklyn_rad.tag.tag.pattern")
     */
    private $tag;


    /**
     * @return string|null
     */
    public function getTag () : ?string
    {
        return $this->tag;
    }


    /**
     * @param string|null $tag
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
