<?php

namespace App\Form;

use App\Entity\Rencontre;
use App\Entity\Resultat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('scoreJoueur1', IntegerType::class, [
                'label' => 'Score du Joueur 1',
                'attr' => [
                    'placeholder' => '0 à 3',
                    'min' => 0,
                    'max' => 3,
                ],
            ])
            ->add('scoreJoueur2', IntegerType::class, [
                'label' => 'Score du Joueur 2',
                'attr' => [
                    'placeholder' => '0 à 3',
                    'min' => 0,
                    'max' => 3,
                ],
            ])
            ->add('Rencontre', EntityType::class, [
                'class' => Rencontre::class,
                'choice_label' => function (Rencontre $rencontre) {
                    return sprintf(
                        'Match %d: %s vs %s',
                        $rencontre->getId(),
                        $rencontre->getJoueur1()->getNom(),
                        $rencontre->getJoueur2()->getNom()
                    );
                },
                'label' => 'Rencontre associée',
                'placeholder' => 'Sélectionnez une rencontre',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Resultat::class,
        ]);
    }
}
