<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Form;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Helper class for calculating form error mappings.
 */
class FormErrorMapper
{
    /**
     * @var TranslatorInterface
     */
    private $translator;


    /**
     * @param TranslatorInterface $translator
     */
    public function __construct (TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }


    /**
     * @param FormInterface $form
     *
     * @return string[][]
     */
    private function generateMapping (FormInterface $form, string $translationDomain) : array
    {
        $allErrors = [];
        $formName = $form->getName();
        $fieldPrefix = !empty($formName) ? "{$formName}_" : "";

        $this->addChildErrors($form, $fieldPrefix, $translationDomain, $allErrors);

        if ($form->getErrors()->count() > 0)
        {
            foreach ($form->getErrors() as $topLevelError)
            {
                $allErrors["__global"][] = $this->translator->trans($topLevelError->getMessage(), [], $translationDomain);
            }
        }

        return $allErrors;
    }


    /**
     * Adds all child errors to the mapping of errors.
     *
     * @param FormInterface $form
     * @param string        $fieldPrefix
     * @param array         $allErrors
     */
    private function addChildErrors (FormInterface $form, string $fieldPrefix, string $translationDomain, array &$allErrors) : void
    {
        foreach ($form->all() as $children)
        {
            $childErrors = $children->getErrors();
            $fieldName = \ltrim("{$fieldPrefix}{$children->getName()}");

            if (0 < \count($childErrors))
            {
                $allErrors[$fieldName] = \array_map(
                    function (FormError $error) use ($translationDomain)
                    {
                        return $this->translator->trans($error->getMessage(), [], $translationDomain);
                    },
                    \is_array($childErrors) ? $childErrors : \iterator_to_array($childErrors)
                );
            }

            $this->addChildErrors($children, "{$fieldName}_", $translationDomain, $allErrors);
        }
    }


    /**
     * Generates the form error mapping.
     *
     * @param FormInterface $form
     * @param string        $translationDomain
     *
     * @return string[][]
     */
    public function generate (FormInterface $form, string $translationDomain = "data") : array
    {
        return $this->generateMapping($form, $translationDomain);
    }
}
