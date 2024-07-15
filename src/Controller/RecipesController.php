<?php

namespace App\Controller;

use App\Entity\Recipes;
use App\Repository\RecipesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/recipes', name: 'app_recipes_')]
class RecipesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(RecipesRepository $recipesRepository): Response
    {
        return $this->render('recipes/index.html.twig', [
            'recipes' => $recipesRepository->findByDuratin(35),
        ]);
    }

    #[Route('/{slug}-{id}', name: 'show', requirements: ['slug' => '[a-z0-9-]+', 'id' => '\d+'])]
    public function show(Recipes $Recipe): Response
    {
        return $this->render('recipes/show.html.twig', [
            // 'slug' => $slug,
            // 'id' => $id,
            // 'demo' => '<strong> demo </strong>'
            'recipe' => $Recipe,
        ]);
    }
}
