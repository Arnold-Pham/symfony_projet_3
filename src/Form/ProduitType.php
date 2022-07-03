<?php

namespace App\Form;


use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $taille = ['2XS' => '2XS', 'XS' => 'XS', 'S' => 'S', 'M' => 'M', 'L' => 'L', 'XL' => 'XL', '2XL' => '2XL', '3XL' => '3XL'];
        $collection = ['Homme' => 'M', 'Femme' => 'F'];

        $builder
            ->add('titre', null, [
                'label' => 'Titre',
                'row_attr' => ['class' => 'mb-3 input-group'],
                'attr' => ['placeholder' => 'Saisir le titre']
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'row_attr' => ['class' => 'mb-3 input-group'],
                'attr' => ['placeholder' => 'Description optionnelle']
            ])
            ->add('couleur', ColorType::class, [
                'row_attr' => ['class' => 'mb-3 input-group']
            ])
            ->add('taille', ChoiceType::class, [
                'choices' => $taille,
                'row_attr' => ['class' => 'mb-3 input-group']
            ])
            ->add('collection', ChoiceType::class, [
                'choices' => $collection,
                'row_attr' => ['class' => 'mb-3 input-group']
            ])
            ->add('photo', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => false
            ])
            ->add('prix', null, [
                'label' => 'Prix',
                'row_attr' => ['class' => 'mb-3 input-group'],
                'attr' => ['placeholder' => 'Saisir le prix unitaire']
            ])
            ->add('stock', null, [
                'label' => 'Stock',
                'row_attr' => ['class' => 'mb-3 input-group'],
                'attr' => ['placeholder' => 'Nombre dans le stock']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Produit::class]);
    }
}
