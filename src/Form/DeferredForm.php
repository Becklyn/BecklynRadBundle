<?php declare(strict_types=1);

namespace Becklyn\Rad\Form;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Form config that is not yet completely resolved
 */
final class DeferredForm
{
    private string $type;
    private array $options;


    /**
     */
    public function __construct (string $type, array $options = [])
    {
        $this->type = $type;
        $this->options = $options;
    }


    /**
     */
    public function getType () : string
    {
        return $this->type;
    }


    /**
     */
    public function getOptions () : array
    {
        return $this->options;
    }


    /**
     * @return static
     */
    public function withOptions (array $options) : self
    {
        $modified = clone $this;
        $modified->options = \array_replace($modified->options, $options);
        return $modified;
    }


    /**
     * Creates the form
     */
    public function createForm (FormFactoryInterface $factory, $data = null, array $options = []) : FormInterface
    {
        return $factory->create(
            $this->type,
            $data,
            \array_replace($this->options, $options)
        );
    }
}
