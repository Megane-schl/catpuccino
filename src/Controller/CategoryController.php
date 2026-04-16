<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/category', name: 'app_category_')]
final class CategoryController extends AbstractController
{
    /**
     * Method to find all of the categories in the database
     * @param CategoryRepository $categoryRepository To collect the categories
     * @return Response The list of the categories
     */
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $arrCategories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categoryList' => $arrCategories,
        ]);
    }


    /**
     * Method to create an new category in the database
     * @param Request $request To collect the new category
     * @param EntityManagerInterface $entityManager Use to create the new category
     * @return Response The success or the failure of the creating an category and redirect to the category list
     */
    #[Route('/create', name: 'create')]
    #[IsGranted('ROLE_MODO')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $objCategory = new Category();

        $createForm = $this->createForm(CategoryFormType::class, $objCategory);

        $createForm->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {

            $objCategory->setCreatedAt(new DateTimeImmutable('now'));

            $entityManager->persist($objCategory);
            $entityManager->flush();

            $this->addFlash('success', "La catégorie a été ajoutée");

            return $this->redirectToRoute('app_category_index');
        }

        return $this->render('category/form.html.twig', [
            'createForm'    => $createForm,
            'title'         => 'Ajouter une catégorie',
            'subtitle'      => 'Organisez nos créations gourmandes'
        ]);
    }

    /**
     * Method to update a new category in the database
     * @param Request $request To collect the new category
     * @param EntityManagerInterface $entityManager Use to create the new category
     * @param Category $category The category to update
     * @return Response The success or the failure of the creating a category and redirect to the category list
     */
    #[Route('/{id<\d+>}/update', name: 'update')]
    #[IsGranted('ROLE_MODO')]
    public function update(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {

        $updateForm = $this->createForm(CategoryFormType::class, $category);

        $updateForm->handleRequest($request);

        if ($updateForm->isSubmitted() && $updateForm->isValid()) {

            $category->setUpdatedAt(new DateTimeImmutable('now'));

            $entityManager->flush();

            $this->addFlash('success', "La catégorie " . $category->getName() . " a été modifiée");

            return $this->redirectToRoute('app_category_index');
        }

        return $this->render('category/form.html.twig', [
            'createForm'    => $updateForm,
            'title'         => 'Modifier une catégorie',
            'subtitle'      => 'Édition de : ' . $category->getName()
        ]);
    }
}
