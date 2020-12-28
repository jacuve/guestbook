<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // personalizar los campos. 
        // author es de tipo text, por defecto, y le ponemos label.
        // email, tipo EmailType
        // createdAt, se quita, se genera automaticamente.
        // conference tambien se quita.
        // se anade un submit
        $builder
            ->add('author', null, [
                'label' => 'Your name'
            ])
            ->add('text')
            ->add('email', EmailType::class)
            ->add('photoFilename')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
