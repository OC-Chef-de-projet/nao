<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ObservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('place', TextType::class, array(
                'attr'  => array( 'class' => 'autocomplete'),
            ))
            ->add('watched', TextType::class, array(
                'attr'  => array( 'class' => '', 'autocomplete' => 'off'),
            ))
            ->add('taxref', TextType::class, array(
                'attr'  => array( 'class' => '', 'autocomplete' => 'off'),
            ))
            ->add('individuals', ChoiceType::class, array(
                'attr'  => array( 'class' => '', 'autocomplete' => 'off'),
                'choices'  => array(
                    'Maybe' => null,
                    'Yes' => true,
                    'No' => false,
                ),
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
        return 'observation_form';
    }
}
