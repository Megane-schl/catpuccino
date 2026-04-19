<?php

namespace App\Tests\Application\Controller;

use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Attribute\ResetDatabase;

#[ResetDatabase]
class RegistrationControllerTest extends WebTestCase
{
    /**
     * Method to test if the registration page is accessible
     */
    public function testRegisterPageShow(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        
        $this->assertResponseIsSuccessful();
    }

    /**
     * Method to test if a user can register with valid informations and receive a confirmation email
     */

    public function testRegisterSuccess(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        
        $this->assertResponseIsSuccessful();

        // Complete the form with correct

       $client->submitForm("S'inscrire", [
            'registration_form[lastname]'               => "Grigri",
            'registration_form[firstname]'              => "Grisou",
            'registration_form[email]'                  => "grisou.grisou@gmail.com",
            'registration_form[plainPassword][first]'   => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[plainPassword][second]'  => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[agreeTerms]'             => true,
        ]);

        $this->assertResponseRedirects();

        // Verify that an email is send after the registration
        // Don'y forget to verify that an email is send before the redirection
        // cf. https://symfony.com/doc/current/mailer.html#write-a-functional-test
        $this->assertEmailCount(1);

        // collect the last email send
        $email = $this->getMailerMessage();

        // Test the user that receives the email and the email object
        $this->assertEmailAddressContains($email, 'To', 'grisou.grisou@gmail.com');
        $this->assertEmailSubjectContains($email, "Veuillez confirmer votre email");

        $client->followRedirect();
        $this->assertRouteSame('app_login');
    }

    /**
     * Method to test te register with an email thats already exist in the database
     */
    public function testRegisterWithExistingEmail(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        
        $this->assertResponseIsSuccessful();

        // Complet the form with a email already use
        // => create an user in the database
        $objExtistingUser = UserFactory::createOne();

        // Use the email in the factory
        $client->submitForm("S'inscrire", [
            'registration_form[lastname]'               => "Grigri",
            'registration_form[firstname]'              => "Grisou",
            'registration_form[email]'                  => $objExtistingUser->getEmail(),
            'registration_form[plainPassword][first]'   => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[plainPassword][second]'  => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[agreeTerms]'             => true,
        ]);

        $this->assertResponseStatusCodeSame(422); 

        $this->assertAnySelectorTextContains('div', "Cette adresse e-mail est déjà utilisée");

        $this->assertEmailCount(0);
    }

    /**
     * Method to test to register with password not identical
     */
    public function testRegisterWithMismatchPassword(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        
        $this->assertResponseIsSuccessful();

        // Complete the form with two differents password
        // Use the user email create with the factory
        $client->submitForm("S'inscrire", [
            'registration_form[lastname]'               => "Grigri",
            'registration_form[firstname]'              => "Grisou",
            'registration_form[email]'                  => "grisou.grisou@gmail.com",
            'registration_form[plainPassword][first]'   => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[plainPassword][second]'  => "M?sth3erbe!!hihihiMYS",
            'registration_form[agreeTerms]'             => true,
        ]);

        $this->assertResponseStatusCodeSame(422); 

        $this->assertAnySelectorTextContains('div', "Le mot de passe doit être identique");

        $this->assertEmailCount(0);
    }

    /**
     * Method to test to register without agree the terms
     */
    public function testRegisterWithoutAgreeTerms(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        
        $this->assertResponseIsSuccessful();

         $client->submitForm("S'inscrire", [
            'registration_form[lastname]'               => "Grigri",
            'registration_form[firstname]'              => "Grisou",
            'registration_form[email]'                  => "grisou.grisou@gmail.com",
            'registration_form[plainPassword][first]'   => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[plainPassword][second]'  => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[agreeTerms]'             => false,
        ]);

        $this->assertResponseStatusCodeSame(422);

        $this->assertAnySelectorTextContains('div', "Vous devez accepter les conditions");

        $this->assertEmailCount(0);
    }
}