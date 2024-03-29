<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => "Nom de la figure",
                'attr' => [
                    'class' => 'w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500',
                    'placeholder' => '360, Flip, Grab, ...',
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Catégorie:',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la figure',
                'attr' => [
                    'class' => 'w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500',
                    'placeholder' => "C'est une figure de ...",
                    'rows' => '4',
                ],
            ])
            ->add('pictures', CollectionType::class, [
                'label' => 'Ajouter des images',
                'entry_type' => PictureType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'error_bubbling' => false,
                'attr' => [
                    'class' => 'space-y-2',
                ],
                // Ajoutez les options pour les champs de l'entry_type si nécessaire
            ])
            ->add('video', CollectionType::class, [
                'label' => 'Ajouter des vidéos',
                'entry_type' => VideoType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'error_bubbling' => false,
                'attr' => [
                    'class' => 'space-y-2',
                ],
                // Ajoutez les options pour les champs de l'entry_type si nécessaire
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}