<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/product', name: 'app_product_')]
#[IsGranted('ROLE_MODO')]
final class ProductController extends AbstractController
{
    /**
     * Method to find all of the products in the database
     * @param ProductRepository $productRepository To collect the products
     * @return Response The list of the products
     */
    #[Route('/', name: 'index')]
    public function index(ProductRepository $productRepository): Response
    {
        $arrProducts = $productRepository->findAllActive();
        return $this->render('product/index.html.twig', [
            'productList' => $arrProducts,
        ]);
    }

    /**
     * Method to create a new product in the database
     * @param Request $request To collect the new product
     * @param EntityManagerInterface $entityManager Use to create the new product
     * @param FileUploader $fileUploader Service used to ulpload the product image
     * @return Response The success or the failure of the creating a product and redirect to the product himself
     */
    #[Route('/create', name: 'create')]
    #[IsGranted('ROLE_MODO')]
    public function create(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $objProduct = new Product();

        $createForm = $this->createForm(ProductFormType::class, $objProduct);

        $createForm->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {

            /** @var UploadedFile $objUploadedFile */
            $objUploadedFile = $createForm->get('img')->getData();

            $strNewFilename = $fileUploader->uploadProductImg($objUploadedFile);

            $objProduct->setCreatedAt(new DateTimeImmutable('now'))
                       ->setImg($strNewFilename);

            $entityManager->persist($objProduct);
            $entityManager->flush();

            $this->addFlash('success', "Le produit a été ajouté");

            return $this->redirectToRoute('app_product_index');
        }

        return $this->render('product/form.html.twig', [
            'createForm'    => $createForm,
            'title'         => 'Ajouter un produit',
            'subtitle'      => 'Ajoutez une nouvelle douceur au meownu'
        ]);
    }
}
