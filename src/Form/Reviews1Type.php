<?php

namespace App\Form;

use App\Entity\Reviews;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class Reviews1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_id')
            ->add('product_id')
            ->add('rating')
            ->add('comment')
            ->add('sentiment')
            ->add('created_at', null, [
                'widget' => 'single_text',
                
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
