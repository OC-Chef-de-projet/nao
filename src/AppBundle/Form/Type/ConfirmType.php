<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfirmType extends AbstractType
{
    /**
     * Confirm form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
        ->setAction($options['url'])
        ->add('action', HiddenType::class)
        ->add('id', HiddenType::class)

        ->add('save', SubmitType::class,
            [
                'attr' => array('class' => 'modal-action modal-close waves-effect btn-flat light-orange'),
                'label' => 'confirm',
                'translation_domain' => 'messages',
            ]
        )
        ->getForm();

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'url' => null
        ]);
    }

}
