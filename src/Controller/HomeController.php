<?php

namespace App\Controller;

use App\Repository\CatRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Mailer\MailerInterface;
// use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    /**
     * Method to display the home page 
     * @param ProductRepository $productRepository To collect the latests products to show
     * @param CatRepository $catRepository To collect some cats to show
     * @return response The home page and the news products
     */
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository, CatRepository $catRepository): Response
    {

        $newsProducts = $productRepository->findNewsProducts(4);
        $newsCats = $catRepository->findNewsCats(3);

        return $this->render('home/index.html.twig', [
            'newsProducts'  => $newsProducts,
            'newsCats'      => $newsCats,
        ]);
    }
    /* Email testing*/

    // #[Route('/mail', name: 'app_test_mail')]
    // public function sendMail(MailerInterface $mailer): Response
    // {
    //     $email = (new Email())
    //         ->from('contact@catpuccino.fr')
    //         ->to('test@catpuccino.fr')
    //         ->subject('Objet du mail')
    //         ->text('GRISOU CATPUCCINI!!') // Format TEXT
    //         ->html('<p>See Twig integration for better HTML integration!</p>'); // Format HTML

    //     $mailer->send($email);

    //     return $this->redirectToRoute('app_home');
    // }
}
