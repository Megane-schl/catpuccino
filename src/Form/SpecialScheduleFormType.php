<?php

namespace App\Form;

use App\Entity\SpecialSchedule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialScheduleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'label'  => 'Date de l\'horaire exceptionnel :',
                'widget' => 'single_text',
                'html5'  => false, // <-- html5 was prioritary on flatpickr
                'format' => 'yyyy-MM-dd',
                'attr'   => [
                    'class' => 'flatpickr',
                    'placeholder' => 'Sélectionnez une date...',
                ],
            ])

            ->add('name', TextType::class, [
                'label'     => 'Nom de l\'évènement',
                'required'  => false,
                'attr'   => [
                    'placeholder' => 'Anniversaire de Grisou...',
                ],
            ])

            ->add('isClosed', CheckboxType::class, [
                'label'     => 'Le café est-il exceptionnellement fermé ce jour-ci ?',
                'required'  => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])

            ->add('openTime', TimeType::class, [
                'label'     => 'Heure d\'ouverture du café de ce jour spécial :',
                'widget' => 'single_text',
            ])

            ->add('closeTime', TimeType::class, [
                'label'     => 'Heure de fermeture du café de ce jour spécial :',
                'widget' => 'single_text',

            ])
            ->add('maxPeople', IntegerType::class, [
                'label'             => 'Nombre de clients maximum que le café accepte ce jour spécial :',
                'attr'  => [
                    'min'           => 1,
                    'placeholder'   => '20'

                ],
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
            'data_class' => SpecialSchedule::class,
        ]);
    }
}
