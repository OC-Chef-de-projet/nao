<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Post;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('title', TextType::class,[
                'label' => 'article_title',
                'attr' => [
                    'class' => 'form-control'
                ],
                'data_class' => null,
                'translation_domain' => 'messages',
            ])
            ->add('content', TextareaType::class,
                [
                    'label' => 'article_content',
                    'attr' => [
                        'class' => 'materialize-textarea post-textarea',
                    ],
                    'data_class' => null,
                    'translation_domain' => 'messages',
                ]
            )
            ->add('imagelink', FileType::class, [
                'label' => 'featured_image',
                'data_class' => null,
                'required' => false,
                'attr' => [
                    'class' => 'upload'
                ],
                'translation_domain' => 'messages',
            ])
            ->add('save_draft', SubmitType::class,
                [
                    'attr' => array('class' => 'btn white scroll-to waves-effect'),
                    'label' => 'saving_draft',
                    'translation_domain' => 'messages',
                ]
            )
            ->add('save_published', SubmitType::class,
                [
                    'attr' => array('class' => 'btn waves-effect'),
                    'label' => 'publish_article',
                    'translation_domain' => 'messages',
                ]
            )
            ;
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {


        $resolver->setDefaults(array(
            'data_class' => Post::class,
            'status' => null
        ));
    }
}
