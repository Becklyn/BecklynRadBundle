<?php declare(strict_types=1);

namespace Becklyn\Rad\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Symfony removes "placeholder" attributes for certain configurations.
 * In these cases, the placeholder is re-added as `data-placeholder` attribute (if not already set).
 */
class ChoicePlaceholderFormExtension extends AbstractTypeExtension
{
    private TranslatorInterface $translator;

    /**
     */
    public function __construct (TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function buildView (FormView $view, FormInterface $form, array $options) : void
    {
        $originalAttributes = $form->getConfig()->getAttribute("data_collector/passed_options", [])["placeholder"] ?? null;
        $existingPlaceholder = $view->vars["placeholder"] ?? null;

        if (
            null === $existingPlaceholder
            && !\array_key_exists("data-placeholder", $view->vars["attr"])
            && \is_string($originalAttributes)
        )
        {
            $view->vars["attr"]["data-placeholder"] = $this->translator->trans($originalAttributes, [], "form");
        }
    }


    /**
     * @inheritDoc
     */
    public static function getExtendedTypes () : array
    {
        return [
            ChoiceType::class,
        ];
    }
}
