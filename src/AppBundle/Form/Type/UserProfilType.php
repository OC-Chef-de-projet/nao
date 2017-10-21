<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class UserProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('aboutme', TextareaType::class, array(
                'attr'          => array( 'class' => 'materialize-textarea'),
                'required'      => false
            ))
            ->add('imagepath', FileType::class, array(
                'label'         => false,
                'mapped'        => false,
                'data_class'    => null,
                'required'      => false,
                'attr'          => array('class' => 'upload'),
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/gif',
                            'image/png'
                        ],
                        'mimeTypesMessage'  => 'avatar_format',
                        'maxSizeMessage'    => 'avatar_size',
                    ])
                ]
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
        return 'profil_form';
    }
}