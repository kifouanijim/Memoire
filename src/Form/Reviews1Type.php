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
            //->add('user_id')
            ->add('product_id')
            ->add('comment')
            ->add('sentiment', ChoiceType::class, [
                'choices'  => [
                    'Normal' => 'normal',
                    'Urgent' => 'urgent',
                    'Prioritaire' => 'prioritaire',
                ],
                'expanded' => true, // Affichage sous forme de cases à cocher
                'multiple' => true, // Permet de sélectionner plusieurs valeurs
                ])
            ->add('user', HiddenType::class, [ // Champ caché pour stocker l'ID utilisateur
                'mapped' => false, 
            ]);
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reviews::class,
        ]);
    }
}
