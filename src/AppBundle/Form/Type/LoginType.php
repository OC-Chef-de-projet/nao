<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)

    {
        $builder
            ->add('_email', EmailType::class,
                [
                    'attr' => [
                        'placeholder' => 'Adresse email'
                    ]
                ]
            )
            ->add('_password', PasswordType::class,
                [
                    'attr' => [
                        'placeholder' => 'Mot de passe'
                    ]
                ]
            )
            ->add('save', SubmitType::class,
                [
                    'attr' => array('class' => 'btn btn-success btn-sm'),
                    'label' => 'Connexion'
                ]
            )
            ->add('_remember_me', CheckboxType::class,
                [
                    'data' => true,
                    'required' => false
                ]
            );
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
