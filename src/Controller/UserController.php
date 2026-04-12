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
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class UserController extends AbstractController
{
    /**
     * Method to find all of the users in the database
     * @param UserRepository $userRepository to collect the users
     * @return Response the list of the users
     */
    #[Route('/user', name: 'app_user')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(UserRepository $userRepository): Response
    {
        $arrUsers = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'userList' => $arrUsers
        ]);
    }
    /**
     * Method to create an user
     * @param Request $request The HTTP request
     * @param UserPasswordHasherInterface $userPasswordHasher The password hashed
     * @param EntityManagerInterface $entityManager Use to create the new data
     * @return Response The success or the failure of the creating user and redirect to the user list
     */

    #[Route('/user/create', name: 'app_user_create')]
    #[IsGranted('ROLE_ADMIN')]
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
            'subtitle' => 'Renseignez les informations'
        ]);
    }

    /**
     * Method to update an user
     * @param User The user to update
     * @param Request The HTTP request
     * @param UserPasswordHasherInterface The password hashed
     * @param EntityManagerInterface Use to save and change data
     * @return Response The success or the failure of updating the user and redirect to the user list
     */
    #[Route('/user/{id<\d+>}/update', name: 'app_user_update')]
    #[IsGranted('ROLE_ADMIN')]
    public function update(
        User $user,
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $userForm = $this->createForm(UserInfoFormType::class, $user);

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            /** @var string $plainPassword */
            $plainPassword = $userForm->get('plainPassword')->getData();

            // In case the password was entered
            if ($plainPassword) {

                // encode the plain password
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            }

            $user->setUpdatedAt(new DateTimeImmutable('now'));

            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a été mis à jour");

            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/form.html.twig', [
            'userForm' => $userForm,
            'title'    => 'Modifier un utilisateur',
            'subtitle' => 'Mettez à jour les informations de ' . $user->getFirstname() . ' ' . $user->getLastname()
        ]);
    }

    /**
     * Method to update the roles of an user
     * @param User The user role to update
     * @param Request The HTTP request
     * @param EntityManagerInterface Use to save and change the role of the user
     * @return Response The success or the failure of updating the user and redirect to the user list
     */
    #[Route('/user/{id<\d+>}/roles', name: 'app_user_roles')]
    #[IsGranted('ROLE_ADMIN')]
    public function updateRoles(
        User $user,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $strFormError = "";

        if ($request->isMethod('POST')) {

            $submittedToken = $request->getPayload()->get('_csrf_token');

            if ($this->isCsrfTokenValid('user_role', $submittedToken)) {

                $arrRoles = [];

                if ($request->request->get('user-role-admin')) {
                    $arrRoles[] = 'ROLE_ADMIN';
                }
                if ($request->request->get('user-role-modo')) {
                    $arrRoles[] = 'ROLE_MODO';
                }

                $user->setRoles($arrRoles);
                $entityManager->flush();

                $this->addFlash('success', "Les rôles de l'utilisateur ont été modifiés");

                return $this->redirectToRoute('app_user');
            }

            $strFormError = "Le jeton de sécurité n'est pas valide. Réessayez ou actualisez la page";
        }

        return $this->render('user/roles.html.twig', [
            'user'      => $user,
            'formError' => $strFormError,
            'title'     => 'Affecter des rôles',
            'subtitle'  => 'Modifier les rôles de ' . $user->getFirstname() . ' ' . $user->getLastname()
        ]);
    }

    /**
     * Method to soft delete an user
     * @param User $user The user to delete
     * @param EntityManagerInterface $entityManager Use to save and change the data
     * @return Response The success or the failure of deleting the user and redirect to the user list
     */
    #[Route('/user/{id<\d+>}/delete', name: 'app_user_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
        //in case the admin try to delete himself
        if ($user == $this->getUser()) {

            $this->addFlash('danger', "Vous ne pouvez pas supprimer votre propre compte");
            return $this->redirectToRoute('app_user');
        }

        $user->setDeletedAt(new DateTimeImmutable('now'));
        $entityManager->flush();
        $this->addFlash('success', "L'utilisateur a été supprimé");

        return $this->redirectToRoute('app_user');
    }

    /**
     * Method to ban or urban an user
     * @param User $user The user ban/unban
     * @param EntityManagerInterface $entityManager Use to save and change the data
     * @return Response The success or the failure of banning/unbanning the user and redirect to the user list
     */
    #[Route('/user/{id<\d+>}/ban', name: 'app_user_ban')]
    #[IsGranted('ROLE_ADMIN')]
    public function ban(User $user, EntityManagerInterface $entityManager): Response
    {
        //in case the admin try to ban himself
        if ($user == $this->getUser()) {

            $this->addFlash('danger', "Vous ne pouvez pas bannir votre propre compte");
            return $this->redirectToRoute('app_user');
        }

        if ($user->isBan()) {
            $user->setIsBan(false);
            $this->addFlash('warning', "L'utilisateur a été débanni");
        } else {
            $user->setIsBan(true);
            $this->addFlash('success', "L'utilisateur a été banni");
        }

        $entityManager->flush();
        return $this->redirectToRoute('app_user');
    }
}
