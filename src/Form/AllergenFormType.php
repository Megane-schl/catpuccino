<?php

namespace App\Form;

use App\Entity\Allergen;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AllergenFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'allergène',
                'attr' => [
                    'placeholder' => 'Gluten, Lait...'
                ]
            ])
            ->add('info', TextareaType::class, [
                'label' => 'Description complémentaire',
                'attr' => [
                    'placeholder' => 'Peut contenir des traces de fruits à coque...',
                    'rows'   => 5
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
            'data_class' => Allergen::class,
        ]);
    }
}
