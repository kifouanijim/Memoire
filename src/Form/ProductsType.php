<?php

namespace App\Form;

use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use App\Form\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('created_at', null, [
                'widget' => 'single_text'
            ])
            ->add('user', HiddenType::class, [ // Champ caché pour stocker l'ID utilisateur
                'mapped' => false, 
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
