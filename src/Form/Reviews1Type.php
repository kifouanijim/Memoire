<?php

namespace App\Form;

use App\Entity\Reviews;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class Reviews1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('IMEI', TextType::class, [
                'label' => 'Numéro IMEI',
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire',
            ])
            ->add('niveau', ChoiceType::class, [
                'label' => 'Niveau de priorité',
                'choices' => [
                    'Normal' => 'normal',
                    'Urgent' => 'urgent',
                    'Prioritaire' => 'prioritaire',
                ],
                'expanded' => true,  // Affichage sous forme de boutons radio
                'multiple' => true,  // Permettre plusieurs choix (cases à cocher)
            ])
            ->add('user', HiddenType::class, [
                'mapped' => false, // Ne pas le lier directement à l'entité
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reviews::class,
        ]);
    }
}
