<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Category;
use App\Entity\Document;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'La communauté de l\'anneau',
                ],
                'help' => 'Titre du document dans la langue d\'origine'
            ])
            ->add('description')
            ->add('isbn')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'nameColor',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ])
            ->add('authors', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => ['class' => Author::class, 'choice_label' => 'fullName'],
                'allow_add' => true
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
