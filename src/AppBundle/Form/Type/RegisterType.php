<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'attr'  => array( 'class' => '', 'autocomplete' => 'off'),
            ))
            ->add('email', EmailType::class, array(
                'attr'  => array( 'class' => '', 'autocomplete' => 'off'),
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'password_not_equal',
                'first_options'  => array('label' => false),
                'second_options' => array('label' => false),
            ))
            ->add('newsletter', CheckboxType::class, array(
                'mapped'    => false,
                'label'     => false,
                'required' => false,
                'attr'  => array( 'class' => 'filled-in')
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => true
        ));
    }

    public function getName()
    {
        return 'register_form';
    }
}
