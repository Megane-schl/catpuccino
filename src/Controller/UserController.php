<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserInfoFormType;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    /**
     * Method to find all of the users in the database
     * @param UserRepository $userRepository to collect the users
     * @return Response the list of the users
     */
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        $arrUsers = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'userList' => $arrUsers
        ]);
    }
    /**
     * Method to create an user
     * @param Request $request 
     * @param UserPasswordHasherInterface $userPasswordHasher 
     * @param EntityManagerInterface $entityManager 
     * @return Response 
     */

    #[Route('/user/create', name: 'app_user_create')]
    public function create(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $objUser = new User();

        $userForm = $this->createForm(UserInfoFormType::class, $objUser);

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            /** @var string $plainPassword */
            $plainPassword = $userForm->get('plainPassword')->getData();

            // If the pwd is blank
            if (!$plainPassword) {

                $plainPassword = "default_password";
            }

            // encode the plain password
            $objUser->setPassword($userPasswordHasher->hashPassword($objUser, $plainPassword));

            // set the registration datetime
            $objUser->setCreatedAt(new DateTimeImmutable('now'));

            $entityManager->persist($objUser);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a été créé");

            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/form.html.twig', [
            'userForm' => $userForm,
            'title' => 'Créer un utilisateur',
            'subtitle' => 'Mettre à jour les informations'
        ]);
    }
}
