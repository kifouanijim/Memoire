<?php

namespace App\Form;

use App\Entity\Reviews;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class Reviews1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('product_id')
        ->add('comment')
        ->add('sentiment', ChoiceType::class, [
            'choices' => [
                'Normal' => 'normal',
                'Urgent' => 'urgent',
                'Prioritaire' => 'prioritaire',
            ],
            'expanded' => true,  // Affichage sous forme de boutons radio
            'multiple' => true, // Permettre plusieurs choix (si vous voulez des cases à cocher)
        ])
        ->add('user', HiddenType::class, [
            'mapped' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reviews::class,
        ]);
    }
}
