<?php declare(strict_types=1);

namespace Becklyn\Rad\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CollectionAdditionalOptionsFormExtension extends AbstractTypeExtension
{
    private const LABELS = ["empty_message", "entry_add_label", "entry_remove_label"];

    /**
     * @inheritDoc
     */
    public function buildForm (FormBuilderInterface $builder, array $options) : void
    {
        foreach (self::LABELS as $label)
        {
            $builder->setAttribute($label, $options[$label]);
        }
        $builder->setAttribute("allow_sort", $options["allow_sort"]);
    }

    /**
     * @inheritDoc
     */
    public function buildView (FormView $view, FormInterface $form, array $options) : void
    {
        foreach (self::LABELS as $label)
        {
            $view->vars[$label] = $form->getConfig()->getAttribute($label);
        }
        $view->vars["allow_sort"] = $form->getConfig()->getAttribute("allow_sort");
    }

    /**
     * @inheritDoc
     */
    public function configureOptions (OptionsResolver $resolver) : void
    {
        $resolver
            ->setDefined(self::LABELS)
            ->setDefined("allow_sort")
            ->setDefault("allow_sort", false)
            ->setAllowedTypes("allow_sort", "bool")
            ->setDefaults(\array_fill_keys(self::LABELS, null));

        foreach (self::LABELS as $label)
        {
            $resolver->setAllowedTypes($label, ["string", "null"]);
        }
    }

    /**
     * @inheritDoc
     */
    public static function getExtendedTypes () : array
    {
        return [
            CollectionType::class,
        ];
    }
}
