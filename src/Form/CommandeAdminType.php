<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Membre;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $etat = ['En cours' => 'En cours', 'Envoyé' => 'Envoyé', 'Livré' => 'Livré'];

        $builder
            ->add('id_produit', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'titre',
                'label' => 'Produit',
                'row_attr' => ['class' => 'mb-3 input-group'],
            ])
            ->add('quantite', null, [
                'label' => 'Quantité',
                'row_attr' => ['class' => 'mb-3 input-group'],
                'attr' => ['placeholder' => 'Quantité souhaité.']
            ])
            ->add('etat', ChoiceType::class, [
                'label' => 'Etat',
                'row_attr' => ['class' => 'mb-3 input-group'],
                'choices' => $etat
            ])
            ->add('id_membre', EntityType::class, [
                'class' => Membre::class,
                'choice_label' => 'pseudo',
                'label' => 'Membre',
                'row_attr' => ['class' => 'mb-3 input-group'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Commande::class]);
    }
}
