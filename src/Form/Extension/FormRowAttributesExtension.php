<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Adds a new form option "row_attr", that acts like "attr" but is rendered as
 * attributes on the form row and not the form widget.
 */
class FormRowAttributesExtension extends AbstractTypeExtension
{
    /**
     * @inheritDoc
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute("row_attr", $options["row_attr"]);
    }


    /**
     * @inheritDoc
     */
    public function buildView (FormView $view, FormInterface $form, array $options)
    {
        $view->vars["row_attr"] = $form->getConfig()->getAttribute("row_attr");
    }


    /**
     * @inheritDoc
     */
    public function configureOptions (OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(["row_attr"])
            ->setAllowedTypes("row_attr", "array")
            ->setDefaults([
                "row_attr" => [],
            ]);
    }


    /**
     * @inheritDoc
     */
    public static function getExtendedTypes () : array
    {
        return [
            FormType::class,
        ];
    }
}
