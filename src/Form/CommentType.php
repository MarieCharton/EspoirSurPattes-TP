<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content',TextareaType::class,[
                "label" => "Poster un commentaire : ",
                "required" => true,
                'attr' => [
                    'placeholder' => "Les commentaires sont un espace d’expression des avis des internautes, merci de respecter l’avis de chacun. Nous encourageons les internautes à rester courtois afin que le débat se déroule dans les meilleures conditions.🙂!"
                ]
            ])
            ->add('envoyer', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
