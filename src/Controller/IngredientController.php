<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientFormType;
use App\Repository\IngredientRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/ingredient', name: 'app_ingredient_')]
final class IngredientController extends AbstractController
{
    /**
     * Method to find all of the ingredients in the database
     * @param IngredientRepository $ingredientRepository To collect the ingredients
     * @return Response The list of the ingredients
     */
    #[Route('/', name: 'index')]
    #[IsGranted('ROLE_MODO')]
    public function index(IngredientRepository $ingredientRepository): Response
    {
        $arrIngredients = $ingredientRepository->findAll();
        return $this->render('ingredient/index.html.twig', [
            'ingredientList' => $arrIngredients,
        ]);
    }

    /**
     * Method to create an new ingredient in the database
     * @param Request $request To collect the new ingredient
     * @param EntityManagerInterface $entityManager Use to create the new ingredient
     * @return Response The success or the failure of the creating an ingredient and redirect to the ingredient list
     */
    #[Route('/create', name: 'create')]
    #[IsGranted('ROLE_MODO')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $objIngredient = new Ingredient();

        $createForm = $this->createForm(IngredientFormType::class, $objIngredient);

        $createForm->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {

            $objIngredient->setCreatedAt(new DateTimeImmutable('now'));

            $entityManager->persist($objIngredient);
            $entityManager->flush();

            $this->addFlash('success', "L'ingrédient a été ajouté");

            return $this->redirectToRoute('app_ingredient_index');
        }


        return $this->render('ingredient/form.html.twig', [
            'createForm'    => $createForm,
            'title'         => 'Ajouter un ingrédient',
            'subtitle'      => 'Un nouvel ingrédient pour nos recettes'
        ]);
    }

    /**
     * Method to create an new ingredient in the database
     * @param Request $request To collect the new ingredient
     * @param EntityManagerInterface $entityManager Use to create the new ingredient
     * @param Ingredient $ingredient The ingredient to update
     * @return Response The success or the failure of the creating an ingredient and redirect to the ingredient list
     */
    #[Route('/{id<\d+>}/update', name: 'update')]
    #[IsGranted('ROLE_MODO')]
    public function update(Ingredient $ingredient, Request $request, EntityManagerInterface $entityManager): Response
    {

        $updateForm = $this->createForm(IngredientFormType::class, $ingredient);

        $updateForm->handleRequest($request);

        if ($updateForm->isSubmitted() && $updateForm->isValid()) {

            $ingredient->setUpdatedAt(new DateTimeImmutable('now'));

            $entityManager->flush();

            $this->addFlash('success', "L'ingrédient " . $ingredient->getName() . " a été modifié");

            return $this->redirectToRoute('app_ingredient_index');
        }

        return $this->render('ingredient/form.html.twig', [
            'createForm'    => $updateForm,
            'title'         => 'Modifier un ingredient',
            'subtitle'      => 'Édition de : ' . $ingredient->getName()
        ]);
    }

    /**
     * Method to soft delete an ingredient
     * @param Ingredient $ingredient The ingredient to delete
     * @param EntityManagerInterface $entityManager Use to save and change the data
     * @return Response The success or the failure of deleting the ingredient and redirect to the alleingredientrgen list
     */
    #[Route('/{id<\d+>}/delete', name: 'delete', methods: ['POST'])]
    #[IsGranted('ROLE_MODO')]
    #[IsCsrfTokenValid('delete-ingredient', '_csrf_token')]
    public function delete(Ingredient $ingredient, EntityManagerInterface $entityManager): Response
    {
        try {
            $ingredient->setDeletedAt(new DateTimeImmutable('now'));
            $entityManager->flush();
            $this->addFlash('success', "L'ingredient a été supprimé");
        } catch (Exception $exc) {
            $this->addFlash('danger', "Une erreur est survenue. Réessayez");
        }

        return $this->redirectToRoute('app_ingredient_index');
    }
}
