<?php

namespace Becklyn\RadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Default login form
 */
class LoginForm extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add("username", EmailType::class, [
                "label" => "E-Mail-Adresse",
                "required" => true,
                "attr" => [
                    "placeholder" => "E-Mail-Adresse",
                ],
            ])
            ->add("password", PasswordType::class, [
                "label" => "Passwort",
                "required" => true,
                "attr" => [
                    "placeholder" => "Passwort",
                ],
            ]);
    }



    /**
     * @inheritdoc
     */
    public function configureOptions (OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            "csrf_parameter" => "_token",
            "csrf_token_id" => "authenticate",
        ]);
    }



    /**
     * @inheritdoc
     */
    public function getBlockPrefix ()
    {
        return "appLogin";
    }
}
