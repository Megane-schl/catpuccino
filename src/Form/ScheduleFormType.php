<?php

namespace App\Form;

use App\Entity\Schedule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScheduleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isClose', CheckboxType::class, [
                'label'     => 'Le café est-il fermé ce jour-ci ?',
                'required'  => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('openTime', TimeType::class, [
                'label'     => 'Heure d\'ouverture du café ce jour-ci :',
                'widget' => 'single_text',
            ])
            ->add('closeTime', TimeType::class, [
                'label'     => 'Heure de fermeture du café ce jour-ci :',
                'widget' => 'single_text',
            ])
            ->add('maxPeople', IntegerType::class, [
                'label'     => 'Nombre de clients maximum que le café accepte ce jour-ci :',
            ])

            ->add('submit', SubmitType::class, [
                'label' => "Enregistrer",
                'attr'  => [
                    'class' => 'btn btn-primary w-100'
                ],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Schedule::class,
        ]);
    }
}
