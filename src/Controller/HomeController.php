<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Mailer\MailerInterface;
// use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
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
