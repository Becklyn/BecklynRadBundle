<?php

namespace Becklyn\RadBundle\Type\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 *
 */
class GenericTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('help', $options['help']);
        $builder->setAttribute('cancelUrl', $options['cancelUrl']);
    }



    /**
     * {@inheritdoc}
     */
    public function buildView (FormView $view, FormInterface $form, array $options)
    {
        $view->vars['cancelUrl'] = $form->getConfig()->getAttribute('cancelUrl');
        $view->vars['help']      = $form->getConfig()->getAttribute('help');
    }



    /**
     * @inheritDoc
     */
    public function configureOptions (OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'help'      => null,
            'cancelUrl' => null,
        ]);
    }



    /**
     * {@inheritdoc}
     */
    public function getExtendedType ()
    {
        return 'form';
    }
}
