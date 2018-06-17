<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Form;


use Symfony\Component\Form\FormInterface;


trait FormErrorMappingTrait
{
    /**
     * Returns the form error mapping for the given form
     *
     * @param FormInterface $form
     * @return \string[][]
     */
    protected function getFormErrorMapping (FormInterface $form) : array
    {
        $mapper = new FormErrorMapper();
        return $mapper->generate($form);
    }
}
