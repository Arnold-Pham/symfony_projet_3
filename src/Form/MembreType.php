<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class MembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $sexe = ['Homme' => 'M', 'Femme' => 'F'];
        $choixStatut = ['Membre' => 0, 'Admin' => 1];

        $builder
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'row_attr' => ['class' => 'mb-3 input-group'],
                'choices' => $choixStatut
            ])
            ->add('civilite', ChoiceType::class, [
                'label' => 'Civilité',
                'row_attr' => ['class' => 'mb-3 input-group'],
                'choices' => $sexe
            ])
            ->add('nom', null, [
                'label' => '<i class="bi bi-pencil-fill"></i>',
                'label_html' => true,
                'row_attr' => ['class' => 'mb-3 input-group'],
                'attr' => ['placeholder' => 'Saisir le nom']
            ])
            ->add('prenom', null, [
                'label' => '<i class="bi bi-pencil-fill"></i>',
                'label_html' => true,
                'row_attr' => ['class' => 'mb-3 input-group'],
                'attr' => ['placeholder' => 'Saisir le prénom']
            ])
            ->add('email', null, [
                'label' => '<i class="bi bi-envelope-fill"></i>',
                'label_html' => true,
                'row_attr' => ['class' => 'mb-3 input-group'],
                'attr' => ['placeholder' => 'Saisir l\'email']
            ])
            ->add('pseudo', null, [
                'label' => '<i class="bi bi-person-fill"></i>',
                'label_html' => true,
                'row_attr' => ['class' => 'mb-3 input-group'],
                'attr' => ['placeholder' => 'Saisir le pseudo']
            ])
            ->add('password', PasswordType::class, [
                'label' => '<i class="bi bi-lock-fill"></i>',
                'label_html' => true,
                'row_attr' => ['class' => 'mb-3 input-group'],
                'attr' => ['placeholder' => 'Saisir le mot de passe', 'autocomplete' => 'new-password'],
                'mapped' => false,
                'required' => false,
                'constraints' => [new NotBlank(['message' => 'Entrez un mot de passe']), new Length(['min' => 6, 'max' => 4096])]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Membre::class]);
    }
}
