<?php

namespace App\Tests\Application\Controller;

use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Attribute\ResetDatabase;

#[ResetDatabase]
class RegistrationControllerTest extends WebTestCase
{
    public function testRegisterPageShow(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        
        $this->assertResponseIsSuccessful();
    }

    public function testRegisterSuccess(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        
        $this->assertResponseIsSuccessful();

        // On complète le formulaire avec des informations correctes

        $client->submitForm("S'inscrire", [
            'registration_form[email]'                  => "john.doe@email.com",
            'registration_form[lastname]'               => "Doe",
            'registration_form[firstname]'              => "John",
            'registration_form[birthdate]'              => "2000-04-05",
            'registration_form[plainPassword][first]'   => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[plainPassword][second]'  => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[agreeTerms]'             => true,
        ]);

        $this->assertResponseRedirects();

        // Vérifie qu'un mail est envoyé après l'inscription
        // Attention à vérifier qu'un mail est envoyé AVANT de follow la redirection
        // cf. https://symfony.com/doc/current/mailer.html#write-a-functional-test
        $this->assertEmailCount(1);

        // Récupère le dernier e-mail envoyé
        $email = $this->getMailerMessage();

        // Test le destinataire et l'objet du mail
        $this->assertEmailAddressContains($email, 'To', 'john.doe@email.com');
        $this->assertEmailSubjectContains($email, "Please Confirm your Email");

        $client->followRedirect();
        $this->assertRouteSame('app_dashboard');
    }

    public function testRegisterWithExistingEmail(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        
        $this->assertResponseIsSuccessful();

        // On complète le formulaire avec une adresse email déjà utilisée
        // => créer au préalable un user dans la base
        $objExtistingUser = UserFactory::createOne();

        // On utilise l'email de l'utilisateur créé par la Factory
        $client->submitForm("S'inscrire", [
            'registration_form[email]'                  => $objExtistingUser->getEmail(),
            'registration_form[lastname]'               => "Minou",
            'registration_form[firstname]'              => "Tigrou",
            'registration_form[plainPassword][first]'   => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[plainPassword][second]'  => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[agreeTerms]'             => true,
        ]);

        $this->assertResponseStatusCodeSame(422); //< Code 422 que l'on voit dans le Profiler lors d'un essai en web

        $this->assertAnySelectorTextContains('div', "There is already an account with this email");

        $this->assertEmailCount(0);
    }

    public function testRegisterWithMismatchPassword(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        
        $this->assertResponseIsSuccessful();

        // On complète le formulaire avec des mots de passe différents
        // On utilise l'email de l'utilisateur créé par la Factory
        $client->submitForm("S'inscrire", [
            'registration_form[email]'                  => "john.doe@email.com",
            'registration_form[lastname]'               => "Doe",
            'registration_form[firstname]'              => "John",
            'registration_form[birthdate]'              => "2000-04-05",
            'registration_form[plainPassword][first]'   => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[plainPassword][second]'  => "M?sth3erbe!!zzzzzzzzz",
            'registration_form[agreeTerms]'             => true,
        ]);

        $this->assertResponseStatusCodeSame(422); //< Code 422 que l'on voit dans le Profiler lors d'un essai en web

        $this->assertAnySelectorTextContains('div', "Les champs doivent être identiques");

        $this->assertEmailCount(0);
    }

    public function testRegisterWithoutAgreeTerms(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        
        $this->assertResponseIsSuccessful();

        // On complète le formulaire en ne cochant pas la case "Agree terms"
        $client->submitForm("S'inscrire", [
            'registration_form[email]'                  => "john.doe@email.com",
            'registration_form[lastname]'               => "Doe",
            'registration_form[firstname]'              => "John",
            'registration_form[birthdate]'              => "2000-04-05",
            'registration_form[plainPassword][first]'   => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[plainPassword][second]'  => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[agreeTerms]'             => false,
        ]);

        $this->assertResponseStatusCodeSame(422);

        $this->assertAnySelectorTextContains('div', "Vous devez accepter les conditions");

        $this->assertEmailCount(0);
    }
}