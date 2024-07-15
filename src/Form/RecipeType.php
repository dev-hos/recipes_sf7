<?php

namespace App\Form;

use App\Entity\Recipes;
use DateTimeZone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecipeType extends AbstractType
{
    public function __construct(private readonly SluggerInterface $slugger)
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
            ->add('duration')
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoSlug(...))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->timestamp(...))
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ]);
    }

    private function autoSlug(PreSubmitEvent $event): void
    {

        $data = $event->getData();
        if (empty($data['slug'])) {
            $data['slug'] = strtolower($this->slugger->slug($data['title']));
            $event->setData($data);
        }
    }

    private function timestamp(PostSubmitEvent $postSubmitEvent): void
    {
        $timezone = new DateTimeZone('Europe/Paris');
        $data = $postSubmitEvent->getData();
        $data->setUpdatedAt(new \DateTimeImmutable('now', $timezone));
        if (!$data->getId()) {
            $data->setCreatedAt(new \DateTimeImmutable('now', $timezone));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipes::class,
        ]);
    }
}
