<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Form\CatFormType;
use App\Repository\CatRepository;
use App\Service\FileUploader;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/cat', name: 'app_cat_')]
final class CatController extends AbstractController
{
    /**
     * Method to find all of the cats in the database
     * @param CatRepository $catRepository To collect the cats
     * @return Response The list of the cats
     */
    #[Route('/', name: 'index')]
    public function index(CatRepository $catRepository): Response
    {
        $arrCats = $catRepository->findAllActive();
        return $this->render('cat/index.html.twig', [
            'catList' => $arrCats,
        ]);
    }

    /**
     * Method to create a new cat in the database
     * @param Request $request To collect the new cat
     * @param EntityManagerInterface $entityManager Use to create the new cat
     * @param FileUploader $fileUploader Service used to ulpload the cat image
     * @return Response The success or the failure of the creating a cat and redirect to the cat list 
     */
    #[Route('/create', name: 'create')]
    #[IsGranted('ROLE_MODO')]
    public function create(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $objCat = new Cat();

        $createForm = $this->createForm(CatFormType::class, $objCat);

        $createForm->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {

            /** @var UploadedFile $objUploadedFile */
            $objUploadedFile = $createForm->get('img')->getData();

            //default picture
            if ($objUploadedFile) {
                $strNewFilename = $fileUploader->uploadCatsImg($objUploadedFile);
                $objCat->setImg($strNewFilename);
            } else {
                $objCat->setImg('default.png');
            }

            $objCat->setCreatedAt(new DateTimeImmutable('now'));

            $entityManager->persist($objCat);
            $entityManager->flush();

            $this->addFlash('success', "Le chat " . $objCat->getName() . " a été ajouté");

            return $this->redirectToRoute('app_cat_index');
        }

        return $this->render('cat/form.html.twig', [
            'createForm'    => $createForm,
            'title'         => 'Ajouter un chat',
            'subtitle'      => 'Ajoutez une nouvelle boule de poils'
        ]);
    }

    /**
     * Method to update a cat in the database
     * @param Request $request To collect the news informations about the cat
     * @param EntityManagerInterface $entityManager Use to update the cat
     * @param Cat $cat The cat to update
     * @param FileUploader $fileUploader Service to handle the image upload and remove
     * @return Response The success or the failure of updating a product and redirect to the cat list
     */
    #[Route('/{id<\d+>}/update', name: 'update')]
    #[IsGranted('ROLE_MODO')]
    public function update(
        Cat $cat,
        Request $request,
        EntityManagerInterface $entityManager,
        FileUploader $fileUploader
    ): Response {

        $updateForm = $this->createForm(CatFormType::class, $cat);

        $updateForm->handleRequest($request);

        if ($updateForm->isSubmitted() && $updateForm->isValid()) {


            $objUploadedFile  = $updateForm->get('img')->getData();

            //call the fileuploader service
            //if the image is changed
            if ($objUploadedFile) {

                $fileUploader->removeProductImg($cat->getImg());
                $strNewFilename  = $fileUploader->uploadCatsImg($objUploadedFile);
                $cat->setImg($strNewFilename);
            }

            $cat->setUpdatedAt(new DateTimeImmutable('now'));

            $entityManager->flush();

            $this->addFlash('success', "Le chat " . $cat->getName() . " a été mis à jour");

            return $this->redirectToRoute('app_cat_index', [
                'id' => $cat->getId()
            ]);
        }

        return $this->render('cat/form.html.twig', [
            'createForm'    => $updateForm,
            'title'         => 'Modifier un chat',
            'subtitle'      => 'Édition de : ' . $cat->getName()
        ]);
    }

    /**
     * Method to soft delete a cat
     * @param Cat $cat The cat to delete
     * @param EntityManagerInterface $entityManager Use to save and change the data
     * @return Response The success or the failure of deleting the cat and redirect to the cat list
     */
    #[Route('/{id<\d+>}/delete', name: 'delete', methods: ['POST'])]
    #[IsGranted('ROLE_MODO')]
    #[IsCsrfTokenValid('delete-cat', '_csrf_token')]
    public function delete(Cat $cat, EntityManagerInterface $entityManager): Response
    {
        try {
            $cat->setDeletedAt(new DateTimeImmutable('now'));
            $entityManager->flush();
            $this->addFlash('success', "Le chat a été supprimé");
        } catch (Exception $exc) {
            $this->addFlash('danger', "Une erreur est survenue. Réessayez");
        }

        return $this->redirectToRoute('app_cat_index');
    }
}
