<?php

namespace App\Form;

use App\Entity\Allergen;
use App\Entity\Ingredient;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'ingrédient',
                'attr' => [
                    'placeholder' => 'Chocolat, beurre de cacahuète...'
                ]
            ])
            ->add('isVegan', CheckboxType::class, [
                'label'     => 'Cet ingrédient est-il vegan ?',
                'required'  => false, // <-- mean that he is not checked by default
                'attr' => [
                    'class' => 'form-check-input',
                ] 

            ])
            ->add('allergen', EntityType::class, [
                'label' => 'Allergène(s) associé(s)',
                'class' => Allergen::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'expanded' => true 
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
            'data_class' => Ingredient::class,
        ]);
    }
}
