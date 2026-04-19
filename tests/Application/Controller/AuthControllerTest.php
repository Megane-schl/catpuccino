<?php

namespace App\Tests\Application\Controller;

use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Attribute\ResetDatabase;

#[ResetDatabase]
class AuthControllerTest extends WebTestCase
{
    /**
     * Method to test if the login page is accessible
     */
    public function testLoginPageShow(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Method to test if a user can log with a correct email and a correct password and redirected him to the home page
     */
    public function testLoginSuccessWithCorrectCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        // Test the connexion with a existant user
        // Corrects Email and Password

        // Create a user in the database
        $objUser = UserFactory::createOne();

        $client->submitForm('Se connecter', [
            '_username' => $objUser->getEmail(),
            '_password' => UserFactory::DEFAULT_PASSWORD
        ]);

        // Valide if the login went good et reditorect to home page 
        $this->assertResponseRedirects();

        $client->followRedirect();
        $this->assertRouteSame('app_home');
    }

    /**
     * Method to test the error message if the user enter a wrong password and display the correct error message
     */
    public function testLoginFailedWithBadPassword(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $objUser = UserFactory::createOne();

        $client->submitForm('Se connecter', [
            '_username' => $objUser->getEmail(),
            '_password' => "BadPassword"
        ]);

        // redirection to login page if there is an error
        $this->assertResponseRedirects();

        $client->followRedirect();
        $this->assertRouteSame('app_login');

        // verify that an error message is here
        $this->assertAnySelectorTextContains('div', 'Adresse e-mail ou mot de passe invalide');
    }

    /**
     * Method to test the error message if the user enter a wrong email and display the correct error message
     */
    public function testLoginFailedWithBadEmail(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $client->submitForm('Se connecter', [
            '_username' => "NotExist@mail.com",
            '_password' => "BadPassword"
        ]);

        // redirection to login page if there is an error
        $this->assertResponseRedirects();

        $client->followRedirect();
        $this->assertRouteSame('app_login');

        // verify that an error message is here
        $this->assertAnySelectorTextContains('div', 'Adresse e-mail ou mot de passe invalide');
    }
}
