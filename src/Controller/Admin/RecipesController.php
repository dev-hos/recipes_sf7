<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Entity\Recipes;
use App\Form\RecipeType;
use App\Repository\CategoriesRepository;
use App\Repository\RecipesRepository;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/admin/recipes', name: 'admin.recipes.')]
class RecipesController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(RecipesRepository $recipesRepository): Response
    {
        
        return $this->render('admin/recipes/index.html.twig', [
            'recipes' => $recipesRepository->findAll(),
        ]);
    }

    #[Route('/{slug}-{id}', name: 'show', requirements: ['slug' => '[a-z0-9-]+', 'id' => '\d+'], methods: ['GET'])]
    public function show(Recipes $Recipe): Response
    {
        return $this->render('admin/recipes/show.html.twig', [
            // 'slug' => $slug,
            // 'id' => $id,
            // 'demo' => '<strong> demo </strong>'
            'recipe' => $Recipe,
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $recipe = new Recipes();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'La recette a bien été créée');
            return $this->redirectToRoute('admin.recipes.index');
        }

        return $this->render('admin/recipes/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['slug' => '[a-z0-9-]+', 'id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Recipes $Recipe, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RecipeType::class, $Recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'La recette a bien été modifiée');
            return $this->redirectToRoute('admin.recipes.index');
        }

        return $this->render('admin/recipes/edit.html.twig', [
            'recipe' => $Recipe,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete (Recipes $recipes, EntityManagerInterface $em): Response
    {
        $em->remove($recipes);
        $em->flush();
        $this->addFlash('success', 'La recette a bien été supprimée');
        return $this->redirectToRoute('admin.recipes.index');
    }


}
