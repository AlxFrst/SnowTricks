<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
->add('name', TextType::class, [
    'label' => 'Nom de la catégorie ',
    'attr' => [
        'class' => 'bg-gray-50 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm',
        'placeholder' => '360, Flip, Grab, ...'
    ]
])
->add('parent', null, [ // Assumant que c'est un EntityType pour la catégorie parente
    'class' => Category::class, // Remplacez Category::class par la classe de votre entité de catégorie
    'choice_label' => 'name', // Assumant que 'name' est le champ à afficher des entités de catégorie
    'label' => 'Référence ',
    'attr' => [
        'class' => 'bg-gray-50 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm',
        'placeholder' => 'Sélectionnez une catégorie parente'
    ]
])
->add('add', SubmitType::class, [
    'label' => 'Ajouter',
    'attr' => [
        'class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline'
    ]
]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
