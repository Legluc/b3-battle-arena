<?php

namespace App\Form;

use App\Entity\Joueur;
use App\Entity\Rencontre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RencontreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('joueur1', EntityType::class, [
                'class' => Joueur::class,
                'choice_label' => 'id',
            ])
            ->add('joueur2', EntityType::class, [
                'class' => Joueur::class,
                'choice_label' => 'id',
            ])
            ->add('gagnant', EntityType::class, [
                'class' => Joueur::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rencontre::class,
        ]);
    }
}
