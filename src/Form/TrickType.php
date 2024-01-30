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
                    'class' => 'w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500',
                    'placeholder' => 'Nom de la figure',
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'multiple' => true,
                'expanded' => true,
                'label' => 'Catégorie',
                'choice_attr' => function ($category) {
                    return ['class' => 'mr-4 mb-2 p-2 rounded-lg border border-gray-300 hover:bg-gray-100'];
                },
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la figure',
                'attr' => [
                    'class' => 'w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500',
                    'placeholder' => 'Description de la figure',
                ],
            ])
            ->add('pictures', CollectionType::class,[
                'entry_type' => PictureType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'error_bubbling' => false,
          ])
            ->add('video',CollectionType::class,[
            'entry_type' => VideoType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete'=> true,
                'error_bubbling' => false
          ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}