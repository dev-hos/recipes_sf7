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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Image;

class RecipeType extends AbstractType
{
    public function __construct(private readonly FormListenerFactory $factory)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'empty_data' => ''
            ])
            ->add('slug', TextType::class, [
                'required' => false,
            ])
            ->add('content', TextareaType::class, [
                'empty_data' => ''
            ])
            ->add('duration', IntegerType::class)
            ->add('category', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
                'empty_data' => '',
            ])
            ->add('thumbnailFile', FileType::class)
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->factory->autoSlug('title'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->factory->timestamp())
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipes::class,
        ]);
    }
}
