<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $this->roleChoice = $options['role_choice'];

        $builder
            ->add('role', ChoiceType::class,[
                'choices' => $this->roleChoice,
                'choice_translation_domain' => 'messages'
            ])
            ->add('inactive', CheckboxType::class, [
                    'required' => false,
                ]
            )->add('cancel', SubmitType::class,
                [
                    'attr' => array('class' => 'btn waves-effect waves-light btn-cancel'),
                    'label' => 'annuler',
                    'translation_domain' => 'messages',
                ]
            )
            ->add('save', SubmitType::class,
                [
                    'attr' => array('class' => 'btn waves-effect waves-light btn-validate'),
                    'label' => 'enregistrer',
                    'translation_domain' => 'messages',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
            'role_choice' => null,
        ]);
    }

}
