<?php

namespace Becklyn\RadBundle\Form;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;


/**
 * Helper class for calculating form error mappings
 */
class FormErrorMapping
{
    /**
     * @var string[][]
     */
    private $mapping = [];



    /**
     * @param FormInterface $form
     */
    public function __construct (FormInterface $form)
    {
        $this->mapping = $this->generateMapping($form);
    }



    /**
     * @param FormInterface $form
     *
     * @return string[][]
     */
    private function generateMapping (FormInterface $form)
    {
        $allErrors   = array();
        $formName    = $form->getName();
        $fieldPrefix = !empty($formName) ? "{$formName}_" : "";

        $this->addChildErrors($form, $fieldPrefix, $allErrors);

        if ($form->getErrors()->count() > 0)
        {
            foreach ($form->getErrors() as $topLevelError)
            {
                $allErrors["__global"][] = $topLevelError->getMessage();
            }
        }

        return $allErrors;
    }



    /**
     * Adds all child errors to the mapping of errors
     *
     * @param FormInterface $form
     * @param string        $fieldPrefix
     * @param array         $allErrors
     */
    private function addChildErrors (FormInterface $form, $fieldPrefix, array &$allErrors)
    {
        foreach ($form->all() as $children)
        {
            $childErrors = $children->getErrors();
            $fieldName = ltrim("{$fieldPrefix}{$children->getName()}");

            if (0 < count($childErrors))
            {
                $allErrors[$fieldName] = array_map(
                    function (FormError $error)
                    {
                        return $error->getMessage();
                    },
                    is_array($childErrors) ? $childErrors : iterator_to_array($childErrors)
                );
            }

            $this->addChildErrors($children, "{$fieldName}_", $allErrors);
        }
    }



    /**
     * @return \string[][]
     */
    public function getMapping ()
    {
        return $this->mapping;
    }
}
