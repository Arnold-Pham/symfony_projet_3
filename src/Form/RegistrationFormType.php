<?php

namespace App\Form;


use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $sexe = ['Monsieur' => 'M', 'Madame' => 'F'];

        $builder
            ->add('civilite', ChoiceType::class, [
                'label' => 'Civilité',
                'choices' => $sexe,
                'row_attr' => ['class' => 'mb-3 input-group'],
            ])
            ->add('nom', null, [
                'label' => '<i class="bi bi-pencil-fill"></i>',
                'label_html' => true,
                'row_attr' => ['class' => 'mb-3 input-group'],
                'attr' => ['placeholder' => 'Saisir Nom']
            ])
            ->add('prenom', null, [
                'label' => '<i class="bi bi-pencil-fill"></i>',
                'label_html' => true,
                'row_attr' => ['class' => 'mb-3 input-group'],
                'attr' => ['placeholder' => 'Saisir Prénom']
            ])
            ->add('email', null, [
                'label' => '<i class="bi bi-envelope-fill"></i>',
                'label_html' => true,
                'row_attr' => ['class' => 'mb-3 input-group'],
                'attr' => ['placeholder' => 'email@exemple.com']
            ])
            ->add('pseudo', null, [
                'label' => '<i class="bi bi-person-fill"></i>',
                'label_html' => true,
                'row_attr' => ['class' => 'mb-3 input-group'],
                'attr' => ['placeholder' => 'Saisir Pseudo']
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => '<i class="bi bi-lock-fill"></i>',
                'label_html' => true,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Saisir mot de passe'],
                'row_attr' => ['class' => 'mb-3 input-group'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'max' => 4096
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
