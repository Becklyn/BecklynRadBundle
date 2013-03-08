<?php

namespace OAGM\BaseBundle\Type\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HelpMessageTypeExtension extends AbstractTypeExtension
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('help', $options['help']);
    }



    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView (FormView $view, FormInterface $form, array $options)
    {
        $view->vars['help'] = $form->getConfig()->getAttribute('help');
    }



    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions (OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'help' => null,
        ));
    }



    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType ()
    {
        return 'form';
    }
}