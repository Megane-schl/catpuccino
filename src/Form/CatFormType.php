<?php

namespace App\Form;

use App\Entity\Cat;
use App\Entity\Product;
use App\Enum\CatGender;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CatFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du chat',
                'attr' => [
                    'placeholder' => 'Minou, Grisou, Nougat...',
                ]
            ])
            ->add('birthdate', DateType::class, [
                'label' => 'Date de naissance du chat',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'flatpickr',
                     'placeholder' => '23/01/2020'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du chat',
                'attr' => [
                    'placeholder' => 'Grisou, adore les bâton...'
                ]
            ])
            ->add('img', FileType::class, [
                'label'     => 'Image du chat',
                'mapped'    => false,
                //to keep the old image if not change
                'required'  => false,
            ])

            ->add('gender', EnumType::class, [
                'class' => CatGender::class,
                'label' => 'Sexe',
                'attr' => [
                    'class' => 'rounded-pill',
                ],
                'choice_label' => function(CatGender $gender) // return the value of the enum (to write it in french)
                {
                    return $gender->value;
                }
            ])

            ->add('product', EntityType::class, [
                'class'         => Product::class,
                'label'         => 'Produit favori (optionnel)',
                'required'      => false,
                'choice_label'  => 'name',
                'placeholder' => 'Aucun produit favori',
                'attr' => [
                    'class' => 'rounded-pill',
                ]
            ])


            ->add('submit', SubmitType::class, [
                'label' => "Enregistrer",
                'attr'  => [
                    'class' => 'btn btn-primary w-100'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cat::class,
        ]);
    }
}
