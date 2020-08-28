<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class AccountProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                        new NotBlank(['message' => 'Email manquant']),
                        new Length([
                                        'max' => 180,
                                        'maxMessage' => 'L\'adresse email ne peut contenir plus de {{ limit }} caractéres.'
                        ]),
                        new Email(['message' => 'cette adresse n\'est pas une adresse email valide.']),
                ]
            ])
            ->add('pseudo', TextType::class, [
                        'constraints' => [
                                            new NotBlank(['message' => 'Pseudo manquant.']),
                                            new Length([
                                                            'min' => 3,
                                                            'minMessage' => 'Le pseudo doit contenir au moin {{ limit }} caractéres.',
                                                            'max' => 30,
                                                            'minMessage' => 'Le pseudo ne peut pas contenir plus de {{ limit }} caractéres.',
                                            ]),
                                            new Regex([
                                                            'pattern' => '/^[a-zA-Z0-9_-]+$/',
                                                            'message' => 'Le pseudo ne peut contenir que des chiffres , lettres, tirets et underscores.',
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
