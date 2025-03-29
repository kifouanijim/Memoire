<?php

namespace App\Form;

use App\Entity\Sales;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SalesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('product_id', ChoiceType::class, [
            'choices' => [
                'iPhone 15' => 'iPhone 15',
                'iPhone 14' => 'iPhone 14',
                'iPhone 13' => 'iPhone 13',
                'iPhone 12' => 'iPhone 12',
                'iPhone 11' => 'iPhone 11',
                'iPhone X'  => 'iPhone X',
                'iPhone 8'  => 'iPhone 8',
                'iPhone 7'  => 'iPhone 7',
                'iPhone 6'  => 'iPhone 6',
            ],
            'label' => 'Modèle d\'iPhone',
            'placeholder' => 'Sélectionnez un iPhone',
            'required' => true,
        ])
            ->add('nombre')
            ->add('prix')
            ->add('user', HiddenType::class, [ // Champ caché pour stocker l'ID utilisateur
                'mapped' => false, 
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sales::class,
        ]);
    }
}
