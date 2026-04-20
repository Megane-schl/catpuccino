<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserInfoFormType;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user', name: 'app_user_')]
final class UserController extends AbstractController
{
    /**
     * Method to find all of the users in the database
     * @param UserRepository $userRepository to collect the users
     * @param PaginatorInterface paginator To handle the pagination
     * @param Request $request To collect the searching in the URL
     * @return Response the list of the users
     */
    #[Route('/', name: 'index')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $strSearchName = $request->query->getString('search_name');

        $query = $userRepository->createPaginationQuery($strSearchName);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            $request->query->getInt('perPage', 10) /* limit per page */
        );

        return $this->render('user/index.html.twig', [
            'searchName'  => $strSearchName,
            'pagination'  => $pagination
        ]);
    }
    /**
     * Method to create an user
     * @param Request $request The HTTP request
     * @param UserPasswordHasherInterface $userPasswordHasher The password hashed
     * @param EntityManagerInterface $entityManager Use to create the new data
     * @return Response The success or the failure of the creating user and redirect to the user list
     */

    #[Route('/create', name: 'create')]
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

            return $this->redirectToRoute('app_user_index');
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
    #[Route('/{id<\d+>}/update', name: 'update')]
    #[IsGranted('ROLE_USER')]
    #[IsGranted('USER_EDIT', subject: 'user', message: "Droit insuffisant pour la modification")]
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


            //adapt it depend if its a connected user

            if ($user === $this->getUser()) {
                $this->addFlash('success', "Votre profil a été mis à jour");
                return $this->redirectToRoute('app_dashboard');
            }
            $this->addFlash('success', "L'utilisateur a été mis à jour");
            return $this->redirectToRoute('app_user_index');
        }

        if ($this->getUser() === $user) {
            return $this->render('user/form.html.twig', [
                'userForm' => $userForm,
                'title'    => 'Mon profil',
                'subtitle' => 'Modifier mes informations'
            ]);
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
    #[Route('/{id<\d+>}/roles', name: 'roles')]
    #[IsGranted('ROLE_ADMIN')]
    #[IsGranted('USER_ROLE', subject: 'user', message: "Droit insuffisant pour la modification")]
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

                return $this->redirectToRoute('app_user_index');
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
    #[Route('/{id<\d+>}/delete', name: 'delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    #[IsGranted('USER_DELETE', subject: 'user', message: "Droit insuffisant pour la suppression")]
    #[IsCsrfTokenValid('delete-user', '_csrf_token')]
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
        try {
            $user->setDeletedAt(new DateTimeImmutable('now'));
            $entityManager->flush();
            $this->addFlash('success', "L'utilisateur a été supprimé");
        } catch (Exception $exc) {
            $this->addFlash('danger', "Une erreur est survenue. Réessayez");
        }

        return $this->redirectToRoute('app_user_index');
    }

    /**
     * Method to ban or urban an user
     * @param User $user The user ban/unban
     * @param EntityManagerInterface $entityManager Use to save and change the data
     * @return Response The success or the failure of banning/unban the user and redirect to the user list
     */
    #[Route('/{id<\d+>}/ban', name: 'ban', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    #[IsGranted('USER_BAN', subject: 'user', message: "Droit insuffisant pour le bannissement")]
    #[IsCsrfTokenValid('ban-user', '_csrf_token')]
    public function ban(User $user, EntityManagerInterface $entityManager): Response
    {

        try {
            $user->setIsBan(!$user->isBan()); // if the user is ban : unban, if the user is not banned : ban
            $entityManager->flush();
            if ($user->isBan()) {
                $this->addFlash('success', "L'utilisateur " . $user->getFirstname() . ' ' . $user->getLastname() . " a été banni");
            } else {
                $this->addFlash('success', "L'utilisateur " . $user->getFirstname() . ' ' . $user->getLastname() . " a été débanni");
            }
        } catch (Exception $exc) {
            $this->addFlash('danger', "Une erreur est survenue");
        }

        return $this->redirectToRoute('app_user_index');
    }
}
