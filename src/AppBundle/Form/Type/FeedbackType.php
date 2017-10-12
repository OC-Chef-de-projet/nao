<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'attr'  => array( 'class' => ''),
            ))
            ->add('email', EmailType::class, array(
                'attr'  => array( 'class' => ''),
            ))
            ->add('object', TextType::class, array(
                'attr'  => array( 'class' => ''),
            ))
            ->add('message', TextareaType::class, array(
                'attr'  => array( 'class' => 'materialize-textarea'),
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
        return 'feedback_form';
    }
}
