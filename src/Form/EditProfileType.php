<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Email',
                    'class' => 'w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
                ],
            ])
            ->add('fullName', null, [
                'label' => 'Nom complet',
                'attr' => [
                    'placeholder' => 'Nom complet',
                    'class' => 'w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
                ],
            ])
            ->add('profilePicture', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Merci de choisir un fichier jpg ou png',
                    ]),
                ],
                'attr' => [
                    'class' => 'w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500',
                ],
            ])
            ->add('Modify', SubmitType::class, [
                'label' => 'Modifier',
                'attr' => [
                    'class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg mt-4',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
