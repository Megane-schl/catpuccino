<?php

namespace App\Controller;

use App\Entity\Allergen;
use App\Form\AllergenFormType;
use App\Repository\AllergenRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/allergen', name: 'app_allergen_')]
final class AllergenController extends AbstractController
{
    /**
     * Method to find all of the allergens in the database
     * @param AllergenRepository $allergenRepository To collect the allergens
     * @return Response The list of the allergens
     */
    #[Route('/', name: 'index')]
    #[IsGranted('ROLE_MODO')]
    public function index(AllergenRepository $allergenRepository): Response
    {
        $arrAllergens = $allergenRepository->findAll();
        return $this->render('allergen/index.html.twig', [
            'allergenList' => $arrAllergens,
        ]);
    }

    /**
     * Method to create a new allergen in the database
     * @param Request $request To collect the new allergen
     * @param EntityManagerInterface $entityManager Use to create the new allergen
     * @return Response The success or the failure of the creating an allergen and redirect to the allergen list
     */
    #[Route('/create', name: 'create')]
    #[IsGranted('ROLE_MODO')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $objAllergen = new Allergen();

        $createForm = $this->createForm(AllergenFormType::class, $objAllergen);

        $createForm->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {

            $objAllergen->setCreatedAt(new DateTimeImmutable('now'));

            $entityManager->persist($objAllergen);
            $entityManager->flush();

            $this->addFlash('success', "L'allergène a été ajouté");

            return $this->redirectToRoute('app_allergen_index');
        }


        return $this->render('allergen/form.html.twig', [
            'createForm'    => $createForm,
            'title'         => 'Ajouter un allergène',
            'subtitle'      => 'Saisir les informations'
        ]);
    }

    /**
     * Method to update an allergen in the database
     * @param Request $request To collect the news informations about the allergen
     * @param EntityManagerInterface $entityManager Use to update the allergen
     * @param Allergen $allergen The allergen to update
     * @return Response The success or the failure of updating an allergen and redirect to the allergen list
     */
    #[Route('/{id<\d+>}/update', name: 'update')]
    #[IsGranted('ROLE_MODO')]
    public function update(Allergen $allergen, Request $request, EntityManagerInterface $entityManager): Response
    {

        $updateForm = $this->createForm(AllergenFormType::class, $allergen);

        $updateForm->handleRequest($request);

        if ($updateForm->isSubmitted() && $updateForm->isValid()) {

            $allergen->setUpdatedAt(new DateTimeImmutable('now'));

            $entityManager->flush();

            $this->addFlash('success', "L'allergène " . $allergen->getName() . " a été modifié");

            return $this->redirectToRoute('app_allergen_index');
        }

        return $this->render('allergen/form.html.twig', [
            'createForm'    => $updateForm,
            'title'         => 'Modifier un allergène',
            'subtitle'      => 'Édition de : ' . $allergen->getName()
        ]);
    }

    /**
     * Method to soft delete an allergen
     * @param Allergen $allergen The allergen to delete
     * @param EntityManagerInterface $entityManager Use to save and change the data
     * @return Response The success or the failure of deleting the allergen and redirect to the allergen list
     */
    #[Route('/{id<\d+>}/delete', name: 'delete', methods: ['POST'])]
    #[IsGranted('ROLE_MODO')]
    #[IsCsrfTokenValid('delete-allergen', '_csrf_token')]
    public function delete(Allergen $allergen, EntityManagerInterface $entityManager): Response
    {
        try {
            $allergen->setDeletedAt(new DateTimeImmutable('now'));
            $entityManager->flush();
            $this->addFlash('success', "L'allergène a été supprimé");
        } catch (Exception $exc) {
            $this->addFlash('danger', "Une erreur est survenue. Réessayez");
        }

        return $this->redirectToRoute('app_allergen_index');
    }
}
