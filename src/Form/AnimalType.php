<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Animal;
use App\Entity\Department;
use App\Repository\DepartmentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class, ["label" => "Nom de l'animal","required" =>false])
            ->add("status", ChoiceType::class, [
                "choices" => [
                    "perdu" => "perdu",
                    "trouvé" => "trouvé",
                    "aperçu" => "adoption",
                ],
                "multiple" => false,
                "expanded" => true,
                "label" => "Statut"
            ])
            ->add("age",IntegerType::class,["label" => "Age de l'animal","required" => false]
            )
            ->add("identification", ChoiceType::class, [
                "multiple" => false,
                "expanded" => true,
                "choices" => [
                    "Pucé" => "Pucé",
                    "Tatoué" => "Tatoué",
                    "Non Identifié" => "Non Identifié",
                    "Je ne sais pas" => "Je ne sais pas",
                    
                ],
                "label" => "Identification"
            ])
            ->add('description', TextareaType::class, [
                "label" => "Description",
                'attr' => [
                    'placeholder' => "Essayez d'etre le plus précis possible dans la description de l'animal (Collier, état général, endroit etc...)"
                ]
            ])
            ->add('sex', ChoiceType::class, [
                'choices' => [
                    'Male' => 'Male',
                    'Femelle' => 'Femelle',
                    'Je ne sais pas' => 'Je ne sais pas',
                ],
                'multiple' => false,
                "expanded" => true,
                "label" => "Sexe"
            ])
            ->add('area', TextType::class, [
                "label" => "Quartier/secteur",
                "required" => false
                ])
            ->add('city', TextType::class, ["label" => "Ville ou Village"])
            
            ->add('image', FileType::class, [
                'label' => "Image de l'animal",
                'mapped' => false,
                "required" => false
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'name',
                'placeholder' => "Type d'animal",
                'multiple' => false,
            ])
            ->add('department', EntityType::class, [
                    'class' => Department::class,
                    'query_builder' => function (DepartmentRepository $department) {
                        return $department->createQueryBuilder('d')
                            ->orderBy('d.name', 'ASC');
                    },
                    'choice_label' => 'name',
                    'placeholder' => 'Département',
                    'multiple' => false,

                ])
            ->add('Enregistrer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
