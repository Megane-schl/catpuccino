<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => [
                    'placeholder' => 'Latte Macchiato, Iced Americano...',
                ]
            ])
            ->add('price', MoneyType::class, [
                'label'           => 'Prix du produit',
                'currency'        => 'EUR', // <-- put € on the label 
                'attr' => [
                    'placeholder' => '8.50',
                ]
            ])

            ->add('img', FileType::class, [
                'label'     => 'Image du produit',
            ])

            ->add('description', TextareaType::class, [
                'label'     => 'Description du produit',
                'attr' => [
                    'rows' => 5,
                ]
            ])

            //label not in the bdd, only for ux experience!
            ->add('isSeasonal', CheckboxType::class, [
                'label'     => 'Ce produit est-il saisonnier ?',
                'required'  => false, // <-- mean that he is not checked by default
                'mapped'    => false, // <-- tell to symfony that he is not in my entity
                'attr' => [
                    'class' => 'form-check-input',
                ]
            ])
            ->add('seasonName', TextType::class, [
                'label'     => 'Nom de la période',
                'required'  => false,
                'attr' => [
                    'placeholder'   => 'Saint-Valentin, Halloween, Hiver...',
                ],
                'row_attr' => [
                    'class'     => 'seasonal d-none' // <--  hide by default if isSesonal is not checked
                ]
            ])

            ->add('periodStart', DateType::class, [
                'label'             => 'Début de la période',
                'required'          => false,
                'widget'            => 'single_text',
                'attr' => [
                    'class'         => 'flatpickr',
                ],
                'row_attr' => [
                    'class'     => 'seasonal d-none'
                ]
            ])

            ->add('periodEnd', DateType::class, [
                'label'             => 'Fin de la période',
                'required'          => false,
                'widget'            => 'single_text',
                'attr' => [
                    'class'         => 'flatpickr',
                ],
                'row_attr' => [
                    'class'     => 'seasonal d-none'
                ]
            ])

            ->add('category', EntityType::class, [
                'label' => 'Catégorie associée : ',
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
            ])

            ->add('ingredients', EntityType::class, [
                'label' => 'Composition du produit :',
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,

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
            'data_class' => Product::class,
        ]);
    }
}
