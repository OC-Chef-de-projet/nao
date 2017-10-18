<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('private', CheckboxType::class, array(
                'label'     => false,
                'required' => false,
                'attr'  => array( 'class' => 'filled-in')
            ))
            ->remove('newsletter')
            ->add('newsletter', CheckboxType::class, array(
                'mapped'    => false,
                'label'     => false,
                'required' => false,
                'attr'  => array( 'class' => 'filled-in')
            ))
            ->get('plainPassword')->setRequired(false)
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => true
        ));
    }

    public function getParent()
    {
        return RegisterType::class;
    }
}
