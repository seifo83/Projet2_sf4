<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class MotdepasseAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message'  => 'les mots de passe est incorrecte. Réessayer!',

            // instead of being set onto the object directly, (le chmp n'est pas lié à l'objet User formuliare)
            // this is read and encoded in the controller (Le MDP sera hashé depuis le controlleur)
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Mot de passe manquant.',
                ]),
                new Regex([
                    'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                    'message' => 'Veuillez saisir un mot de passe composé de 8 caractères dont une MAJ, une minuscule, un chiffre et une caractère spécial (-!?$#@).',
                    ]),

                new Length([
                    'min' => 6,
                    'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractéres.',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                           ])
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
