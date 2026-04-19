<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/product', name: 'app_product_')]
final class ProductController extends AbstractController
{
    /**
     * Method to find all of the products in the database
     * @param ProductRepository $productRepository To collect the products
     * @param CategoryRepository $categoryRepository To collect the categories for the filter
     * @param PaginatorInterface paginator To handle the pagination
     * @param Request $request To collect the searching in the URL
     * @return Response The list of the products
     */
    #[Route('/', name: 'index')]
    public function index(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $strSearchName = $request->query->getString('search_name');

        $blGluten = $request->query->getBoolean('gluten');
        $blLactose = $request->query->getBoolean('lactose');
        $blVegan = $request->query->getBoolean('vegan');
        $intCategory = $request->query->getInt('category_filter');
        $arrCategories = $categoryRepository->findAll();

        $query = $productRepository->createPaginationQuery($strSearchName, $blVegan, $blGluten, $blLactose, $intCategory);


        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            $request->query->getInt('perPage', 9),/* limit per page */
            [
                'wrap-queries' => true, /* paginator option for a groupby */
            ]

        );

        return $this->render('product/index.html.twig', [
            'searchName'        => $strSearchName,
            'pagination'        => $pagination,
            'gluten'            => $blGluten,
            'vegan'             => $blVegan,
            'lactose'           => $blLactose,
            'categorySelect'    => $intCategory,
            'categoryList'      => $arrCategories,

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

            // if there is not an image, put a default image
            if ($objUploadedFile) {
                $strNewFilename = $fileUploader->uploadProductImg($objUploadedFile);
                $objProduct->setImg($strNewFilename);
            } else {
                $objProduct->setImg('default.png');
            }

            $objProduct->setCreatedAt(new DateTimeImmutable('now'));

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

    /**
     * Method to show the details of one product
     * @param Product $product To product to show
     * @param ProductRepository $productRepository 
     * @return Response The informations of the specific product
     */
    #[Route('/{id<\d+>}', name: 'show')]
    public function show(Product $product, ProductRepository $productRepository): Response
    {
        $arrAllergens = $productRepository->findUniqueAllergensInProduct($product->getId());

        return $this->render('product/show.html.twig', [
            'product'       => $product,
            'allergenList'  => $arrAllergens
        ]);
    }

    /**
     * Method to update a product in the database
     * @param Request $request To collect the news informations about the product
     * @param EntityManagerInterface $entityManager Use to update the product
     * @param Product $product The product to update
     * @param FileUploader $fileUploader Service to handle the image upload and remove
     * @return Response The success or the failure of updating a product and redirect to the product details
     */
    #[Route('/{id<\d+>}/update', name: 'update')]
    #[IsGranted('ROLE_MODO')]
    public function update(
        Product $product,
        Request $request,
        EntityManagerInterface $entityManager,
        FileUploader $fileUploader
    ): Response {

        $updateForm = $this->createForm(ProductFormType::class, $product);

        $updateForm->handleRequest($request);

        if ($updateForm->isSubmitted() && $updateForm->isValid()) {


            $objUploadedFile  = $updateForm->get('img')->getData();

            //call the fileuploader service
            //if the image is changed
            if ($objUploadedFile) {

                $fileUploader->removeProductImg($product->getImg());
                $strNewFilename  = $fileUploader->uploadProductImg($objUploadedFile);
                $product->setImg($strNewFilename);
            }

            $product->setUpdatedAt(new DateTimeImmutable('now'));

            $entityManager->flush();

            $this->addFlash('success', "Le produit " . $product->getName() . " a été mis à jour");

            return $this->redirectToRoute('app_product_show', [
                'id' => $product->getId()
            ]);
        }

        return $this->render('product/form.html.twig', [
            'createForm'    => $updateForm,
            'title'         => 'Modifier un produit',
            'subtitle'      => 'Édition de : ' . $product->getName()
        ]);
    }

    /**
     * Method to soft delete a product
     * @param Product $product The product to delete
     * @param EntityManagerInterface $entityManager Use to save and change the data
     * @return Response The success or the failure of deleting the product and redirect to the product list
     */
    #[Route('/{id<\d+>}/delete', name: 'delete', methods: ['POST'])]
    #[IsGranted('ROLE_MODO')]
    #[IsCsrfTokenValid('delete-product', '_csrf_token')]
    public function delete(Product $product, EntityManagerInterface $entityManager): Response
    {
        try {
            $product->setDeletedAt(new DateTimeImmutable('now'));
            $entityManager->flush();
            $this->addFlash('success', "Le produit a été supprimé");
        } catch (Exception $exc) {
            $this->addFlash('danger', "Une erreur est survenue. Réessayez");
        }

        return $this->redirectToRoute('app_product_index');
    }
}
