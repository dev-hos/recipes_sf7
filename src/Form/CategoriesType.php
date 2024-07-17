<?php

namespace App\Form;

use App\Entity\Recipes;
use App\Entity\Categories;
use App\Services\FormListenerFactory;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoriesType extends AbstractType
{
    public function __construct(private readonly FormListenerFactory $factory)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'empty_data' => ''
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug',
                'empty_data' => '',
                'required' => false
            ])
            /*
            ->add('recipes', EntityType::class, [
                'class' => Recipes::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'label' => 'Recettes',
                'by_reference' => false
                ])
            */
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->factory->autoSlug('name'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->factory->timestamp())
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }
}
